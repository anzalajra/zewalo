<?php

namespace App\Livewire;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('livewire.layouts.guest')]
class TenantLogin extends Component
{
    public string $email = '';

    public string $password = '';

    public string $errorMessage = '';

    public int $rateLimitSeconds = 0;

    /**
     * Authenticate and redirect to tenant admin panel.
     */
    public function login(): void
    {
        $this->validate([
            'email' => 'required|email|max:150',
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        // Rate limiting: max 5 attempts per 5 minutes per IP
        $rateLimitKey = 'tenant-login:'.request()->ip();
        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            $this->rateLimitSeconds = RateLimiter::availableIn($rateLimitKey);
            $this->errorMessage = "Terlalu banyak percobaan. Coba lagi dalam {$this->rateLimitSeconds} detik.";

            return;
        }
        RateLimiter::hit($rateLimitKey, 300);

        $this->errorMessage = '';

        // Search across all tenants for a user with this email who has admin role
        $tenants = Tenant::with('domains')->get();
        $matchedTenant = null;
        $matchedUser = null;

        foreach ($tenants as $tenant) {
            if ($tenant->domains->isEmpty()) {
                continue;
            }

            if ($tenant->status === 'suspended') {
                // Check if user exists in this suspended tenant
                $userExists = false;
                $tenant->run(function () use (&$userExists) {
                    $userExists = User::where('email', $this->email)
                        ->whereHas('roles', fn ($q) => $q->whereIn('name', ['super_admin', 'admin', 'staff']))
                        ->exists();
                });

                if ($userExists) {
                    $this->errorMessage = 'Toko terkait akun Anda sedang dinonaktifkan. Hubungi support@zewalo.com untuk bantuan.';

                    return;
                }

                continue;
            }

            $user = null;
            $tenant->run(function () use (&$user) {
                $user = User::where('email', $this->email)
                    ->where('is_system_admin', false)
                    ->whereHas('roles', fn ($q) => $q->whereIn('name', ['super_admin', 'admin', 'staff']))
                    ->first();
            });

            if ($user) {
                $matchedTenant = $tenant;
                $matchedUser = $user;
                break;
            }
        }

        if (! $matchedTenant || ! $matchedUser) {
            $this->errorMessage = 'Email atau password salah.';

            return;
        }

        // Verify password
        if (! Hash::check($this->password, $matchedUser->password)) {
            $this->errorMessage = 'Email atau password salah.';

            return;
        }

        // Clear rate limiter on success
        RateLimiter::clear($rateLimitKey);

        // Generate impersonation token and redirect
        $token = tenancy()->impersonate(
            $matchedTenant,
            (string) $matchedUser->id,
            '/admin',
            'web'
        );

        $domain = $matchedTenant->domains->first()->domain;
        $scheme = app()->environment('production') ? 'https' : 'http';
        $url = "{$scheme}://{$domain}/impersonate/{$token->token}";

        $this->redirect($url);
    }

    public function render()
    {
        return view('livewire.tenant-login');
    }
}
