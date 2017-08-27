<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        $this->app->bind('App\Services\Interfaces\ICustomProductService', 'App\Services\CustomProductService');
        $this->app->bind('App\Services\Interfaces\ICustomOrderService', 'App\Services\CustomOrderService');
        $this->app->bind('App\Services\Interfaces\IUserService', 'App\Services\UserService');
        $this->app->bind('App\Services\Interfaces\IAuthService', 'App\Services\AuthService');
        $this->app->bind('App\Services\Interfaces\IMessageService', 'App\Services\MessageService');
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
