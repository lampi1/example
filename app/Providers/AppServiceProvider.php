<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Lang;
use App\Providers\LocalizationServiceProvider;
use App\Daran\DaranServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (!$this->app->runningInConsole()){
            // $socials = Cache::remember('socials', 1, function () {
            //     return \App\Models\Settings\Social::get();
            // });
            //
            // $ecommerce_settings = Cache::remember('ecommerce_settings', 1, function () {
            //     return \App\Models\Settings\Ecommerce::last()->first();
            // });

            $contacts = Cache::remember('contacts', 60, function () {
                return \App\Daran\Models\Settings\Contact::where('lang',Lang::getLocale())->first();
            });



            //View::share('socials', $socials);
            // View::share('brandings', $brandings);
            // View::share('contacts', $contacts);
            //View::share('ecommerce_settings', $ecommerce_settings);
            View::share('contacts', $contacts);


        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(LocalizationServiceProvider::class);
        $this->app->register(DaranServiceProvider::class);

    }
}
