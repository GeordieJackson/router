<?php
    
    use GeordieJackson\Router\Classes\Route;
    
    Route::get('api/v1/api-1', 'ApiController@index')->name('api1');
    Route::get('api/v1/api-2/{id}', 'ApiController@show', ['class' => 'css-className'])->name('api2');
    
    Route::get('api/v1/api-3', function() {
        echo "This is a callback";
    })->name('api3');
    
    Route::get('api/v1/api-4/{id}/edit', function() {
        echo "This is a post page callback";
    })->name('api4');
    
    Route::get('api/v1/login', 'ApiController@login')->name('apiLogin');
    Route::post('api/v1/login', 'ApiController@authenticate');
