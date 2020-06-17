<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
// use App\Daran\Models\Item;
use App\Daran\Models\Slider;
use App\Daran\Models\Page;
use App\Daran\Models\Contact as DBContact;
use App\Mails\ContactReceived;
use App\Mails\NewsletterReceived;
use Illuminate\Support\Facades\DB;
use MetaTag;
use Newsletter;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $page = Page::where('locale',Lang::getLocale())->where('slug','=',trans('routes.permalinks.homepage'))->first();

        if(Lang::getLocale()=='it'){
            MetaTag::set('title', 'Ideas to connect brands');
            MetaTag::set('description', 'Da 25 anni siamo specializzati nel creare partnership tra brand per aggiungere valore all\'esperienza di ingaggio, di acquisto e di post-vendita.');
            MetaTag::set('og_title', 'Ideas to connect brands');
            MetaTag::set('og_description', 'Da 25 anni siamo specializzati nel creare partnership tra brand per aggiungere valore all\'esperienza di ingaggio, di acquisto e di post-vendita.');
        }else{
            MetaTag::set('title', 'Ideas to connect brands');
            MetaTag::set('description', 'For 25 years we have specialized in creating partnerships between brands to add value to the experience of engagement, purchase and after-sales.');
            MetaTag::set('og_title', 'Ideas to connect brands');
            MetaTag::set('og_description', 'For 25 years we have specialized in creating partnerships between brands to add value to the experience of engagement, purchase and after-sales.');
        }

        return view('front.page.homepage',['page' => $page]);
    }

    public function subscribe(Request $request)
    {
        if(!$request->email){
            $error = true;
        }else{
            // if(Lang::getLocale()=='it'){
            //     Newsletter::subscribeOrUpdate($request->email);
            // }else{
            //     Newsletter::subscribeOrUpdate($request->email,[],'subscribers_en');
            // }
            $error = Newsletter::getLastError();

            DB::table('newsletters')->insert([
                'email' => $request->email,
                'lang' => Lang::getLocale()
            ]);

            Mail::to(config('mail.from.mail_newsletter_address'))->send(new NewsletterReceived($request->email,Lang::getLocale()));
        }

        $status = $error ? 'ko' : 'ok';

        if($request->ajax()){
            return array('status'=>$status,'message'=>trans('common.newsletter_thanks'),'error'=>$error);
        }else{
            if(!$error){
                return Redirect::back()->with('message', trans('common.newsletter_thanks'));
            }else{
                return Redirect::back()->with('error', $error);
            }
        }
    }

    public function askInfo(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'surname' => 'required|max:255',
            'email' => 'required|email|max:100',
            'message' => 'required'
        ]);

        $contatto = DBContact::create($request->except(['privacy']));
        $contatto->privacy = \Carbon\Carbon::now();
        $contatto->ip = $request->ip();

        $contatto->save();
        if($request->type == 'work'){
            Mail::to(config('mail.from.mail_work_address'))->send(new ContactReceived($contatto));
        }else{
            Mail::to(config('mail.from.mail_admin_address'))->send(new ContactReceived($contatto));
        }

        if($request->newsletter){
            DB::table('newsletters')->insert([
                'email' => $request->email,
                'lang' => Lang::getLocale()
            ]);
            Mail::to(config('mail.from.mail_newsletter_address'))->send(new NewsletterReceived($request->email,Lang::getLocale()));
        }

        if($request->ajax()){
            return array('status'=>'ok','message'=>trans('contact.contact_ok'));
        }else{
            return Redirect::back()->with('message', trans('contact.contact_ok'));
        }
    }
}
