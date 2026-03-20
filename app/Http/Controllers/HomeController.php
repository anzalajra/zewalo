<?php

namespace App\Http\Controllers;

use App\Enums\TenantFeature;
use App\Models\Category;
use App\Models\Product;
use App\Services\PricingService;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(PricingService $pricingService)
    {
        // Show landing page on central domain
        $centralDomains = config('tenancy.central_domains', []);
        if (in_array(request()->getHost(), $centralDomains, true)) {
            $plans = $pricingService->getAllPlansWithPricing(request());

            return view('landing.index', compact('plans'));
        }

        // Check if storefront is enabled for this tenant
        $tenant = tenant();
        if ($tenant && ! $tenant->hasFeature(TenantFeature::Storefront)) {
            return view('frontend.storefront-disabled');
        }

        $featuredProducts = Product::with(['category', 'units'])
            ->where('is_active', true)
            ->visibleForCustomer(Auth::guard('customer')->user())
            ->whereHas('units', function ($query) {
                $query->where('status', 'available')
                    ->where(function ($q) {
                        $q->whereNull('warehouse_id')
                            ->orWhereHas('warehouse', function ($wq) {
                                $wq->where('is_active', true)
                                    ->where('is_available_for_rental', true);
                            });
                    });
            })
            ->take(8)
            ->get();

        $categories = Category::withCount(['products' => function ($query) {
            $query->where('is_active', true);
        }])->get();

        return view('frontend.home', compact('featuredProducts', 'categories'));
    }
}
