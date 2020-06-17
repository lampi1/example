<?php

namespace App\Daran;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Daran\MetaTags\MetaTagsServiceProvider;
use App\Daran\Cart\CartServiceProvider;
use App\Daran\Payment\PaymentServiceProvider;
use App\Daran\Shipment\ShipmentServiceProvider;
use App\Daran\Providers\EventServiceProvider;
use App\Daran\MetaTags\Facades\MetaTag;
use App\Daran\Cart\Facades\Cart;

class DaranServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishAssets();
        $this->registerResources();
        $this->registerRoutes();
        $this->registerConfigs();

        $this->app->register(MetaTagsServiceProvider::class);
        $this->app->register(CartServiceProvider::class);
        $this->app->register(PaymentServiceProvider::class);
        $this->app->register(ShipmentServiceProvider::class);
        $this->app->register(EventServiceProvider::class);

        $this->app->alias(MetaTag::class, 'MetaTag');
        $this->app->alias(Cart::class, 'Cart');

    }

    /**
     * Register the package resources such as routes, templates, etc.
     *
     * @return void
     */
    protected function registerResources()
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'daran');
        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'daran');;
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/routes.php');
        });

        Route::group($this->guestRouteConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/routes-guest.php');
        });

        Route::group($this->apiRouteConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/routes-api.php');
        });

        Route::group($this->apiManagerRouteConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/routes-api-manager.php');
        });
    }

    /**
     * Publish the public assets.
     *
     * @return void
     */
    protected function publishAssets()
    {
        $this->publishes([
            __DIR__.'/resources/assets' => public_path('vendor/daran'),
        ], 'public');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(\App\Daran\Cloner\Cloner::class, function ($app) {
            return new \App\Daran\Cloner\Cloner($app['events']);
        });

    }

    protected function routeConfiguration()
    {
        return [
            'namespace' => 'App\Daran\Http\Controllers',
            'as' => 'admin.',
            'prefix' => 'admin',
            'middleware' => ['web','auth:admin'],
        ];
    }

    protected function guestRouteConfiguration()
    {
        return [
            'namespace' => 'App\Daran\Http\Controllers',
            'as' => 'admin.',
            'prefix' => 'admin',
            'middleware' => ['web'],
        ];
    }

    protected function apiRouteConfiguration()
    {
        return [
            'namespace' => 'App\Daran\Http\Controllers\Api',
            'as' => 'admin-api.',
            'prefix' => 'admin-api',
            'middleware' => ['api'],
        ];
    }

    protected function apiManagerRouteConfiguration()
    {
        return [
            'namespace' => 'App\Daran\Http\Controllers\ApiManager',
            'as' => 'admin-api-manager.',
            'prefix' => 'admin-api-manager',
            'middleware' => ['api'],
        ];
    }

    protected function registerConfigs()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/auth.php', 'auth'
        );
        $this->publishes([
            __DIR__.'/config/auth.php' => config_path('auth.php'),
        ]);
        $this->publishes([
            __DIR__.'/config/cart.php' => config_path('cart.php'),
        ]);
        $this->publishes([
            __DIR__.'/config/meta-tags.php' => config_path('meta-tags.php'),
        ]);
        $this->publishes([
            __DIR__.'/config/payment.php' => config_path('payment.php'),
        ]);
        $this->publishes([
            __DIR__.'/config/shipment.php' => config_path('shipment.php'),
        ]);
        $this->publishes([
            __DIR__.'/config/daran.php' => config_path('daran.php'),
        ]);
    }
}
