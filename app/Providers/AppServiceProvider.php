<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('App\Services\Interfaces\IProductService', 'App\Services\ProductService');
        $this->app->bind('App\Services\Interfaces\IOrderService', 'App\Services\OrderService');
        $this->app->bind('App\Services\Interfaces\IUserService', 'App\Services\UserService');
        $this->app->bind('App\Services\Interfaces\IAuthService', 'App\Services\AuthService');

        Validator::extend('alpha_num_spaces', function ($attribute, $value) {
            //matches letters, numbers and spaces
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
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
