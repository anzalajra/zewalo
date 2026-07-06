<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    // Impersonation route — auto-login from central admin via magic link token
    Route::get('/impersonate/{token}', function (string $token) {
        return \Stancl\Tenancy\Features\UserImpersonation::makeResponse($token);
    })->name('tenant.impersonate');

    // Note: Do NOT register GET / here — it conflicts with web.php routes.
    // The tenant frontend is served by web.php's HomeController which detects
    // tenant vs central domain automatically.

    // Admin PWA manifest + service worker — PUBLIC (the browser fetches these
    // without session cookies), but tenant-scoped so name/icon come from this
    // tenant's Settings.
    Route::get('/admin/manifest.webmanifest', [\App\Http\Controllers\AdminPwaController::class, 'manifest'])->name('admin.pwa.manifest');
    Route::get('/admin/manifest.json', [\App\Http\Controllers\AdminPwaController::class, 'manifest'])->name('admin.pwa.manifest.json');
    Route::get('/admin/sw.js', [\App\Http\Controllers\AdminPwaController::class, 'serviceWorker'])->name('admin.pwa.sw');

    // Admin-only helper routes (unit label PNG, global scan resolve). Gated by the
    // Filament admin `auth` guard; they run inside tenant context via this group.
    Route::middleware('auth')->group(function () {
        // Admin PWA push subscription management (auth required).
        Route::get('/admin/push/public-key', [\App\Http\Controllers\AdminPwaController::class, 'publicKey'])->name('admin.pwa.public-key');
        Route::post('/admin/push/subscribe', [\App\Http\Controllers\AdminPwaController::class, 'subscribe'])->name('admin.pwa.subscribe');
        Route::post('/admin/push/unsubscribe', [\App\Http\Controllers\AdminPwaController::class, 'unsubscribe'])->name('admin.pwa.unsubscribe');
        Route::post('/admin/push/test', [\App\Http\Controllers\AdminPwaController::class, 'test'])->name('admin.pwa.test');

        // Stream a printable QR/Barcode label PNG for a unit/kit serial.
        Route::get('/admin/unit-label/{serial}/{type}', function (string $serial, string $type) {
            if (! in_array($type, ['qr', 'barcode'], true)) {
                abort(404);
            }

            $png = app(\App\Services\LabelImageService::class)->png($serial, $type);
            $safe = preg_replace('/[^A-Za-z0-9._-]+/', '_', $serial);

            return response()->streamDownload(
                fn () => print ($png),
                "label-{$safe}-{$type}.png",
                ['Content-Type' => 'image/png']
            );
        })->where('serial', '[^/]+')->name('admin.unit-label');

        // Resolve a scanned unit/kit code (closed-system PREFIX:serial or a raw serial)
        // to the product edit page. Used by the global admin QR scanner.
        Route::get('/admin/scan-resolve', function (\Illuminate\Http\Request $request) {
            $codes = app(\App\Services\UnitCodeService::class);
            $raw = trim((string) $request->query('code', ''));
            if ($raw === '') {
                return response()->json(['ok' => false, 'message' => 'Kode kosong.'], 422);
            }

            // Closed-system code first; fall back to treating the scan as a raw serial.
            $serial = $codes->decode($raw) ?? $raw;

            $unit = \App\Models\ProductUnit::where('serial_number', $serial)->first();
            if (! $unit) {
                $kit = \App\Models\UnitKit::where('serial_number', $serial)->first();
                $unit = $kit ? \App\Models\ProductUnit::find($kit->unit_id) : null;
            }

            $product = $unit?->product;
            if (! $product) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Serial tidak ditemukan: '.$serial,
                ], 404);
            }

            // Build the admin catalog edit URL directly — Filament's getUrl() needs the
            // panel context which isn't bootstrapped inside this plain web route.
            return response()->json([
                'ok' => true,
                'url' => url('/admin/products/'.$product->getKey().'/edit'),
                'label' => trim(($product->name ?? 'Produk').' · '.$serial),
            ]);
        })->name('admin.scan-resolve');

        // ---- Bluetooth label printer (LuckPrinter) ----

        // System-data feed for the editor's "import from system" (search / by ids).
        Route::get('/admin/label-printer/units', function (\Illuminate\Http\Request $request) {
            $codes = app(\App\Services\UnitCodeService::class);

            $query = \App\Models\ProductUnit::query()->with(['product', 'kits']);

            if (filled($ids = $request->query('ids'))) {
                $idList = collect(explode(',', (string) $ids))
                    ->map(fn ($i) => (int) trim($i))
                    ->filter()
                    ->all();
                $query->whereIn('id', $idList);
            } elseif (strlen($q = trim((string) $request->query('q', ''))) >= 1) {
                $query->where(function ($w) use ($q) {
                    $w->where('serial_number', 'like', "%{$q}%")
                        ->orWhereHas('product', fn ($p) => $p->where('name', 'like', "%{$q}%"));
                })->limit(30);
            } else {
                $query->limit(20);
            }

            $rows = [];
            foreach ($query->get() as $unit) {
                if (filled($unit->serial_number)) {
                    $rows[] = [
                        'serial' => $unit->serial_number,
                        'name' => $unit->product->name ?? 'Unit',
                        'payload' => $codes->encode($unit->serial_number),
                        'type' => 'unit',
                        'unit_id' => $unit->id,
                    ];
                }
                foreach ($unit->kits as $kit) {
                    if (filled($kit->serial_number)) {
                        $rows[] = [
                            'serial' => $kit->serial_number,
                            'name' => $kit->name,
                            'payload' => $codes->encode($kit->serial_number),
                            'type' => 'kit',
                            'unit_id' => $unit->id,
                        ];
                    }
                }
            }

            return response()->json(['data' => $rows]);
        })->name('admin.label-printer.units');

        // Dedicated full-screen Bluetooth label editor. Serves the standalone
        // LuckPrinter editor (public/vendor/luckprinter/editor.html) as its own HTML
        // document (NOT inside the Filament chrome) with the print queue, logos,
        // calibration and saved templates injected server-side.
        Route::get('/admin/print-label', function (\Illuminate\Http\Request $request) {
            $path = public_path('vendor/luckprinter/editor.html');
            abort_unless(is_file($path), 404);

            $codes = app(\App\Services\UnitCodeService::class);

            // Resolve ?unit / ?units into an ordered print queue server-side.
            $ids = [];
            if (filled($single = $request->query('unit'))) {
                $ids[] = (int) $single;
            }
            if (filled($many = $request->query('units'))) {
                foreach (explode(',', (string) $many) as $part) {
                    if (($id = (int) trim($part)) > 0) {
                        $ids[] = $id;
                    }
                }
            }
            $ids = array_values(array_unique(array_filter($ids)));

            $queue = [];
            if ($ids) {
                $units = \App\Models\ProductUnit::with(['product', 'kits'])
                    ->whereIn('id', $ids)->get()
                    ->sortBy(fn ($u) => array_search($u->id, $ids))->values();

                foreach ($units as $unit) {
                    if (filled($unit->serial_number)) {
                        $queue[] = [
                            'serial' => $unit->serial_number,
                            'name' => $unit->product->name ?? 'Unit',
                            'payload' => $codes->encode($unit->serial_number),
                            'type' => 'unit',
                        ];
                    }
                    foreach ($unit->kits as $kit) {
                        if (filled($kit->serial_number)) {
                            $queue[] = [
                                'serial' => $kit->serial_number,
                                'name' => $kit->name,
                                'payload' => $codes->encode($kit->serial_number),
                                'type' => 'kit',
                            ];
                        }
                    }
                }
            }

            // System logos (settings + brand logos). Zewalo stores these on the
            // private R2 bucket, so serve signed URLs rather than Storage::url().
            // (Canvas use may require the R2 CORS policy to allow the tenant origin.)
            $logos = [];
            $seen = [];
            $addLogo = function (string $name, $value) use (&$logos, &$seen) {
                if (blank($value)) {
                    return;
                }
                $url = \App\Services\Storage\R2Url::signed($value);
                if (blank($url) || isset($seen[$url])) {
                    return;
                }
                $seen[$url] = true;
                $logos[] = ['name' => $name, 'url' => $url];
            };
            $addLogo('Logo Situs', \App\Models\Setting::get('site_logo'));
            $addLogo('Logo Dokumen', \App\Models\Setting::get('doc_logo'));
            $addLogo('Ikon Aplikasi', \App\Models\Setting::get('pwa_admin_icon'));
            foreach (\App\Models\Brand::whereNotNull('logo')->orderBy('name')->get() as $brand) {
                $addLogo('Brand: '.$brand->name, $brand->logo);
            }

            $calib = [
                'x' => (float) \App\Models\Setting::get('luckprinter_calib_x', 0),
                'y' => (float) \App\Models\Setting::get('luckprinter_calib_y', 0),
            ];

            $templates = \App\Models\LabelTemplate::orderByDesc('is_default')
                ->orderBy('name')
                ->get()
                ->map(fn ($t) => [
                    'id' => $t->id,
                    'name' => $t->name,
                    'is_default' => $t->is_default,
                    'design' => $t->design,
                ])->values();
            $defaultTemplate = optional($templates->firstWhere('is_default', true))['design'] ?? null;

            $html = (string) file_get_contents($path);
            $dataUrl = route('admin.label-printer.units', [], false);
            $calibUrl = route('admin.print-label.calib', [], false);
            $templatesUrl = route('admin.print-label.templates.index', [], false);
            $inject = '<script>window.LUCKPRINTER_DATA_URL='.json_encode($dataUrl)
                .';window.LUCKPRINTER_QUEUE='.json_encode($queue)
                .';window.LUCKPRINTER_LOGOS='.json_encode($logos)
                .';window.LUCKPRINTER_CALIB='.json_encode($calib)
                .';window.LUCKPRINTER_CALIB_URL='.json_encode($calibUrl)
                .';window.LUCKPRINTER_TEMPLATES='.json_encode($templates)
                .';window.LUCKPRINTER_TEMPLATES_URL='.json_encode($templatesUrl)
                .';window.LUCKPRINTER_DEFAULT_TEMPLATE='.json_encode($defaultTemplate)
                .';window.LUCKPRINTER_CSRF='.json_encode(csrf_token()).';</script>';
            $html = str_replace('</head>', $inject."\n</head>", $html);

            // Cache-bust the editor bundle by file mtime so a redeploy is picked up.
            $ver = 0;
            foreach (['editor-app.js', 'label.js', 'driver.js', 'devices.js'] as $f) {
                $fp = public_path('vendor/luckprinter/'.$f);
                if (is_file($fp)) {
                    $ver = max($ver, (int) filemtime($fp));
                }
            }
            $html = str_replace(
                '/vendor/luckprinter/editor-app.js',
                '/vendor/luckprinter/editor-app.js?v='.$ver,
                $html
            );

            return response($html)
                ->header('Content-Type', 'text/html; charset=UTF-8')
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
        })->name('admin.print-label');

        // Save Bluetooth label editor print-position calibration.
        Route::post('/admin/print-label/calibration', function (\Illuminate\Http\Request $request) {
            $x = round((float) $request->input('x', 0), 2);
            $y = round((float) $request->input('y', 0), 2);
            \App\Models\Setting::set('luckprinter_calib_x', (string) $x);
            \App\Models\Setting::set('luckprinter_calib_y', (string) $y);

            return response()->json(['ok' => true, 'x' => $x, 'y' => $y]);
        })->name('admin.print-label.calib');

        // Server-saved label templates (CRUD).
        Route::get('/admin/print-label/templates', function () {
            $rows = \App\Models\LabelTemplate::orderByDesc('is_default')
                ->orderBy('name')
                ->get(['id', 'name', 'design', 'is_default']);

            return response()->json(['data' => $rows]);
        })->name('admin.print-label.templates.index');

        Route::post('/admin/print-label/templates', function (\Illuminate\Http\Request $request) {
            $data = $request->validate([
                'id' => ['nullable', 'integer', 'exists:label_templates,id'],
                'name' => ['required', 'string', 'max:120'],
                'design' => ['required', 'array'],
                'is_default' => ['sometimes', 'boolean'],
            ]);

            $template = filled($data['id'] ?? null)
                ? \App\Models\LabelTemplate::findOrFail($data['id'])
                : new \App\Models\LabelTemplate;

            $template->fill([
                'name' => $data['name'],
                'design' => $data['design'],
            ]);
            if ($request->boolean('is_default')) {
                $template->is_default = true;
            }
            $template->save();

            if ($request->boolean('is_default')) {
                $template->setAsDefault();
            }

            return response()->json([
                'ok' => true,
                'template' => $template->only(['id', 'name', 'design', 'is_default']),
            ]);
        })->name('admin.print-label.templates.store');

        Route::delete('/admin/print-label/templates/{template}', function (\App\Models\LabelTemplate $template) {
            $template->delete();

            return response()->json(['ok' => true]);
        })->name('admin.print-label.templates.destroy');
    });
});
