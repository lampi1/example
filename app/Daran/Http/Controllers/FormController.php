<?php

namespace App\Daran\Http\Controllers;

use Illuminate\Http\Request;
use App\Daran\Models\Form;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use App\Daran\Http\Controllers\Controller;
use Illuminate\Support\Str;

class FormController extends Controller
{
    public function index(Request $request)
    {
        // if(!Auth::user()->can('read form')){
        //     abort(503);
        // }

        return view('daran::forms.index');
    }

    public function create(Request $request)
    {
        // if(!Auth::user()->can('create form')){
        //     abort(503);
        // }
        $mode = 'edit';
        $template_id = 0;
        $tmp = config('app.available_translations');
        $locales = array();
        foreach ($tmp as $l) {
            $locales[] = array('key'=>$l, 'value'=>trans('daran::common.'.$l));
        }
        $locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);
        $locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());

        return view('daran::forms.edit', compact('locales', 'locale_group', 'mode', 'template_id', 'locale'));
    }

    public function edit(Form $form)
    {
        // if(!Auth::user()->can('edit form')){
        //     abort(503);
        // }

        $template_id = $form->id;
        $mode = 'edit';
        $locales = array();
        $tmp = config('app.available_translations');
        foreach ($tmp as $l) {
            $locales[] = array('key'=>$l, 'value'=>trans('daran::common.'.$l));
        }
        $locale_group = $form->locale_group;
        $locale = $form->locale;

        return view('daran::forms.edit', compact('locales', 'locale_group', 'mode', 'template_id', 'locale'));
    }

    public function clone(Request $request, int $id)
    {
         if(!Auth::user()->can('create form')){
             abort(503);
         }

         $form = Form::findOrFail($id);
         $clone = $form->duplicate();
         $clone->locale = $request->filled('locale') ? $request->locale : session('working_lang', Lang::getLocale());
         $clone->locale_group = $request->filled('locale_group') ? $request->locale_group : Str::random(20);
         if ($clone->save()) {
             return Redirect::route('admin.forms.edit', ['form' => $clone->id])->with('success', trans('daran::message.success.clone'));
         } else {
             return Redirect::route('admin.forms.index')->with('error', trans('daran::message.error.clone'));
         }
    }

}
