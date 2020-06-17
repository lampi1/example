<?php

namespace App\Daran\Http\Controllers;

use App\Daran\Models\EventCategory;
use Illuminate\Http\Request;
use App\Daran\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EventCategoryController extends Controller
{
    public function index(Request $request)
    {
        if(!Auth::user()->can('read event')){
            abort(503);
        }

        return view('daran::event-categories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function create(Request $request)
     {
         if(!Auth::user()->can('create event')){
             abort(503);
         }
         $eventCategory = new EventCategory();
         $eventCategory->search = 0;
         $eventCategory->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());

         $tmp = config('app.available_translations');
         $locales = array();
         foreach ($tmp as $l) {
             $locales[$l] = trans('daran::common.'.$l);
         }

         $locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);

         return view('daran::event-categories.create', compact('eventCategory', 'locales', 'locale_group'));
     }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!Auth::user()->can('create event')){
            abort(503);
        }

        $this->validate($request, [
            'name' => 'required|max:255',
            'color' => 'required|max:6',
        ]);

        $eventCategory = new EventCategory($request->all());
        $eventCategory->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());
        $eventCategory->locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);

        if ($eventCategory->save()){
            return Redirect::route('event-categories.index')->with('success', trans('daran::message.success.create'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.create'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EventCategory  $eventCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(EventCategory $eventCategory)
    {
        if(!Auth::user()->can('edit event')){
            abort(503);
        }

        $tmp = config('app.available_translations');
        $locales = array();
        foreach ($tmp as $l) {
            $locales[$l] = trans('daran::common.'.$l);
        }
        $locale_group = $eventCategory->locale_group;

        return view('daran::event-categories.edit' , compact('eventCategory', 'locales','locale_group'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EventCategory  $eventCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EventCategory $eventCategory)
    {
        if(!Auth::user()->can('edit event')){
            abort(503);
        }

        $this->validate($request, [
            'name' => 'required|max:255',
            'color' => 'required|max:6',
            'slug' => 'required'
        ]);
        $request['slug'] = Str::slug($request->slug);
        if ($eventCategory->update($request->all())) {
            return Redirect::route('event-categories.index')->with('success', trans('daran::message.success.update'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.update'));
        }
    }
}
