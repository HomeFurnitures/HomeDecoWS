<?php

namespace App\Providers;

use Validator;
use Illuminate\Support\ServiceProvider;

class ValidatorProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('alpha_num_spaces', function ($attribute, $value) {
            return preg_match('/^[a-zA-Z0-9\s]+$/', $value);
        });

        Validator::extend('password', function ($attribute, $value) {
            //matches letters, numbers and .-!@#$%^&*
            return preg_match('/^[a-zA-Z0-9\.\-\!\@\#\$\%\^\&\*]+$/', $value);
        });

        Validator::extend('alpha_spaces', function($attribute, $value)
        {
            return preg_match('/^[\pL\s]+$/u', $value);
        });

        Validator::extend('phone', function ($attribute, $value) {
            return preg_match('/^[0-9\+\-]+$/', $value);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
