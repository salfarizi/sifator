<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
        //validasi gambar base 64
        Validator::extend('base64Image', function ($attribute, $value, $parameters, $validator) {
            // Mengecek tipe file gambar menggunakan getimagesizefromstring()
            $imageInfo = @getimagesizefromstring($value);

            return $imageInfo === false;
        });
    }
}
