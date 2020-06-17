<?php

use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', '\App\Daran\Http\Controllers\SitemapController@index');

Route::group(['middleware' => ['redirect']], function() {
    foreach(App\Daran\Models\Redirection::get() as $redirection){
        Route::get($redirection->from_uri, 'PageController@show');
    }
});

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect']], function(){

    Route::get('/', 'HomeController@index')->name('home');

    //notizie
    Route::get(LaravelLocalization::transRoute('routes.news.index'), ['as'=>'news.index', 'uses' => 'NewsController@index']);
    // Route::get(LaravelLocalization::transRoute('routes.news.category'), ['as'=>'news.category', 'uses' => 'NewsController@category']);
    // Route::get(LaravelLocalization::transRoute('routes.news.view_single'), ['as'=>'news.view', 'uses' => 'NewsController@show']);

    //clienti
    Route::get(LaravelLocalization::transRoute('routes.galleries.index'), ['as'=>'galleries.index', 'uses' => 'PortfolioController@index']);
    Route::get(LaravelLocalization::transRoute('routes.galleries.category'), ['as'=>'galleries.category', 'uses' => 'PortfolioController@category']);
    Route::get(LaravelLocalization::transRoute('routes.galleries.view_single'), ['as'=>'galleries.view', 'uses' => 'PortfolioController@show']);

    //progetti
    Route::get(LaravelLocalization::transRoute('routes.projects.index'), ['as'=>'projects.index', 'uses' => 'ProjectController@index']);
    Route::get(LaravelLocalization::transRoute('routes.projects.view'), ['as'=>'projects.view', 'uses' => 'ProjectController@show']);

    Route::get(LaravelLocalization::transRoute('routes.pages.view'), ['as'=>'pages.view', 'uses' => 'PageController@show']);

    //contatti
    Route::post(LaravelLocalization::transRoute('routes.contacts.info'), ['as'=>'info', 'uses' => 'HomeController@askInfo']);
    //newsletter
    Route::post('/newsletter/subscribe', ['as'=>'newsletter.subscribe', 'uses' => 'HomeController@subscribe']);
});
