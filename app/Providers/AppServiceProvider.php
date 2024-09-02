<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\JenisBarangRepositoryInterface;
use App\Repositories\JenisBarangRepository;
use App\Repositories\BarangRepositoryInterface;
use App\Repositories\BarangRepository;
use App\Repositories\TransaksiRepositoryInterface;
use App\Repositories\TransaksiRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(JenisBarangRepositoryInterface::class, JenisBarangRepository::class);
        $this->app->bind(BarangRepositoryInterface::class, BarangRepository::class);
        $this->app->bind(
            TransaksiRepositoryInterface::class, 
            TransaksiRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
