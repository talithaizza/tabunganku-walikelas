<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Validator;
use Hash;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Database
        Schema::defaultStringLength(191);

        // Memberikan class 'active' otomatis pada navigasi
        require base_path() . '/app/Helpers/frontend.php';

        // SettingsController@ubahPassword
        // Validasi password lama tidak sesuai
        // Check password lama didatabase(terenkripsi) dengan field Password Lama
        Validator::extend('passcheck', function($attribute, $value, $parameters) {
            return Hash::check($value, $parameters[0]);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
