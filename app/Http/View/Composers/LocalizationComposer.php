<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Cache;

class LocalizationComposer {

    public function compose(View $view)
    {
        \Carbon\Carbon::setLocale(Lang::getLocale());

        $brandings = \App\Daran\Models\Settings\Branding::where('lang',Lang::getLocale())->first();

        $contacts = \App\Daran\Models\Settings\Contact::where('lang',Lang::getLocale())->first();

        $view->with('brandings', $brandings);
        $view->with('contacts', $contacts);

    }

}
