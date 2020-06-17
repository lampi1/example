<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class LocalizationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer(
            '*', 'App\Http\View\Composers\LocalizationComposer'
        );
    }
}
