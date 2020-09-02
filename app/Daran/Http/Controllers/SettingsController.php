<?php

namespace App\Daran\Http\Controllers;

use App\Daran\Models\Settings\Contact;
use App\Daran\Models\Settings\Branding;
use App\Daran\Models\Settings\Social;
use App\Daran\Models\Settings\Seo;
use App\Daran\Models\Settings\Ecommerce;
use App\Daran\Models\Country;
use Illuminate\Http\Request;
use App\Daran\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Lang;
use App\Daran\Http\Requests\EcommerceRequest;
use PeterColes\Countries\CountriesFacade as Countries;

class SettingsController extends Controller
{
    public function editGeneralSetting()
    {
        $branding = Branding::where('lang','=',session('working_lang', Lang::getLocale()))->first();
        if(!$branding){
            $branding = Branding::createFromDefault(session('working_lang', Lang::getLocale()));
            $branding->save();
        }

        $seo = Seo::where('type','=','default')->first();
        if (!$seo){
            $seo = Seo::createFromDefault('default');
            $seo->save();
        }

        return view('daran::settings.general.edit', compact('branding', 'seo'));
    }

    public function updateGeneralSetting(Request $request, $branding_id, $seo_id)
    {
        $this->validate($request, [
            'site_name' => 'required|max:255',
            'site_title' => 'required|max:255',
            'site_description' => 'required|max:255',
        ]);

        $branding = Branding::findOrFail($branding_id);
        $branding->site_title = $request->site_title;
        $branding->site_name = $request->site_name;
        $branding->site_description = $request->site_description;

        if($request->file('logo')){
            $file = $request->file('logo');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);
            $nome_originale = 'logo.'.$extension;
            $branding->logo = $this->saveImage($img,$nome_originale, null);
        }

        if($request->file('favicon')){
            $file = $request->file('favicon');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $img = $this->makeImage($file);

            $nome_originale = 'favicon.'.$extension;
            $branding->favicon = $this->saveImage($img,$nome_originale, null);

            $nome_small = 'favicon_ipad.'.$extension;
            $branding->favicon_ipad = $this->saveImage($img,$nome_small,config('daran.images.ipad_icon'));

            $nome_extra_small = 'favicon_iphone.'.$extension;
            $branding->favicon_iphone = $this->saveImage($img,$nome_extra_small,config('daran.images.iphone_icon'));
        }
        $branding->save();

        $seo = Seo::findOrFail($seo_id);
        $seo->update($request->only('title','description','author','og_title','og_description','og_author','og_image','og_type','gtag_manager','g_analytics','faceboox_pixel'));

        if ($seo && $branding) {
            return Redirect::route('admin.settings.edit')->with('success', trans('daran::message.success.update'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.update'));
        }
    }

    public function editContact()
    {
        $contact = Contact::where('lang','=',session('working_lang', Lang::getLocale()))->first();
        if(!$contact){
            $contact = new Contact();
            $contact->lang = Lang::getLocale();
            $contact->save();
        }

        $socials = Social::orderBy('social_name')->get();

        return view('daran::settings.contacts.edit', compact('contact',  'socials'));
    }

    public function updateContact(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);
        $contact->update($request->except('social_name', 'social_url'));

        if ($contact) {
            return Redirect::route('admin.contact-settings.edit')->with('success', trans('daran::message.success.update'));
        } else {
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.update'));
        }
    }

    public function editEcommerce()
    {
        $fees = Ecommerce::orderBy('valid_from','desc')->get();
        $countries = Country::orderBy('code')->get();
        $all_countries = Countries::keyValue('it');
        $keys = Countries::lookup('it')->keys()->toArray();
        $new_countries = Countries::keyValue('it')->reject(function($country, $key) use($keys){
            return in_array($key, $keys);
        });
        return view('daran::settings.ecommerce.edit', compact('fees','countries','all_countries','new_countries'));
    }

    public function updateEcommerce(EcommerceRequest $request)
    {
        $row = ($request->filled('id')) ? Ecommerce::findOrFail($request->id) : new Ecommerece();
        $row->shipping_free_from = $request->shipping_free_from;
        $row->valid_from = $request->valid_from;
        $row->valid_until = $request->valid_until;
        if($row->save()){
            return Redirect::route('admin.ecommerce-settings.edit')->with('success', trans('daran::message.success.create'));
        }else{
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.create'));
        }
    }

    public function updateCountry(Request $request)
    {
        if($request->filled('id')){
            $c = Country::findOrFail($request->id);
            $c->cost = $request->cost;
        }else{
            $found = Country::findOrFail($request->country_code);
            if($found){
                return Redirect::back()->withInput()->with('error', trans('daran::setting.country_exists'));
            }
            $c = new Country();
            $c->code = $request->country_code;
            $c->cost = $request->cost;
        }
        if($c->save()){
            return Redirect::route('admin.ecommerce-settings.edit')->with('success', trans('daran::message.success.create'));
        }else{
            return Redirect::back()->withInput()->with('error', trans('daran::message.error.create'));
        }
    }
}
