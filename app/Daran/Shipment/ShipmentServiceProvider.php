<?php

namespace App\Daran\Shipment;

use Illuminate\Support\ServiceProvider;

class ShipmentServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->singleton(PaymentFactory::class, function ($app) {
        //     return new PaymentFactory($app->config->get('payment', []));dd();
        // });
        $this->app->bind('ShipmentFactory::class', 'App\Daran\Shipment\ShipmentFactory');

    }
}
