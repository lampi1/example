<?php

namespace App\Daran\Payment;

use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
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
        $this->app->bind('PaymentFactory::class', 'App\Daran\Payment\PaymentFactory');
    }
}
