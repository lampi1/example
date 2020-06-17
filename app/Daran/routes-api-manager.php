<?php
    Route::post('/items/update-price', ['as'=>'items.update-price', 'uses' => 'ApiManagerController@updatePrice']);
    Route::put('/items/{code}/update-info', ['as'=>'items.update-info', 'uses' => 'ApiManagerController@updateInfo']);
    Route::put('/items/{code}/update-stock', ['as'=>'item.update-stock', 'uses' => 'ApiManagerController@updateStock']);
    Route::delete('/items/{code}', ['as'=>'items.delete', 'uses' => 'ApiManagerController@destroy']);
