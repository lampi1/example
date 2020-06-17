<?php

namespace App\Daran\Http\Controllers;

use Illuminate\Http\Request;
use App\Daran\Http\Controllers\Controller;
use App\Daran\Models\Order;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Analytics;
use Spatie\Analytics\Period;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $analytics_view_id = config('daran.analytics.view_id');
        $analyticsMostVisitedPages = null;
        $analyticsTotalVisitorsAndPageViews = null;
        if($analytics_view_id){
            try{
                $analyticsMostVisitedPages = Analytics::fetchMostVisitedPages(Period::days(7));
                $analyticsTotalVisitorsAndPageViews = Analytics::fetchTotalVisitorsAndPageViews(Period::days(7));
            }catch(\Exception $e){}
        }

        $ecommerce_enabled = config('daran.ecommerce.enable');
        $orderNumbers = null;
        $orderAmounts = null;
        $latestOrders = null;
        $labelMesi = null;
        if($ecommerce_enabled){
            $to = new Carbon('last day of last month');
            $from = $to->copy()->day(1)->subMonths(12);

            $orderNumbers = Order::GetMonthlyNumber($from,$to)->first();
            $orderAmounts = Order::GetMonthlyAmount($from,$to)->first();

            $latestOrders = Order::with('user')->orderBy('id','desc')->take(10)->get();

            while($from <= $to){
                $labelMesi[] = $from->locale('it')->monthName.' '.$from->year;
                $from=$from->addMonth();
            }
        }

        return view('daran::home', compact('analyticsMostVisitedPages', 'analyticsTotalVisitorsAndPageViews','orderNumbers','orderAmounts','latestOrders','labelMesi'));
    }

    public function setLang($lang)
    {
        $tmp = config('app.available_translations');
        if(in_array($lang,$tmp)){
            session(['working_lang' => $lang]);
        }

        return Redirect::route('admin.dashboard');
    }

}
