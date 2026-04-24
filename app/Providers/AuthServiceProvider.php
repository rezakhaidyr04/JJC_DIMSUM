<?php

namespace App\Providers;

use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Policies\BarangKeluarPolicy;
use App\Policies\BarangMasukPolicy;
// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        BarangMasuk::class => BarangMasukPolicy::class,
        BarangKeluar::class => BarangKeluarPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
