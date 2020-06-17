<?php
    Route::get('/', ['uses' => 'Auth\LoginController@login'])->middleware('guest');
    Route::get('/login', ['as'=>'login', 'uses' => 'Auth\LoginController@login'])->middleware('guest');
    Route::post('/login', ['as'=>'login', 'uses' => 'Auth\LoginController@loginAdmin'])->middleware('guest');
    Route::get('/logout', ['as'=>'logout', 'uses' => 'Auth\LoginController@logout'])->middleware('guest');
    Route::get('/logout', ['as'=>'logout', 'uses' => 'Auth\LoginController@logout'])->middleware('guest');
    Route::get('/password/reset/{token}', ['as'=>'password.reset', 'uses' => 'Auth\ResetPasswordController@showResetForm'])->middleware('guest');
    Route::post('/password/reset', ['as'=>'password.update', 'uses' => 'Auth\ResetPasswordController@reset'])->middleware('guest');
    Route::get('/password/reset', ['as'=>'password.request', 'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm'])->middleware('guest');
    Route::post('/password/email', ['as'=>'password.email', 'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail'])->middleware('guest');
