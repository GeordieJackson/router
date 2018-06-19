<?php
    
    use GeordieJackson\Router\Classes\Route;
    
    Route::get('/', 'PagesController@home')->name('homePage');
    
    Route::get('about-us', 'PagesController@about')->name('aboutPage');
    Route::get('posts/{id}', 'PostsController@show', ['class' => 'css-className'])->name('showPostPage');
    
    Route::get('callback', function() {
        echo "This is a callback";
    })->name('callbackPage');
    
    Route::get('posts/{id}/edit', function() {
        echo "This is a post page callback";
    })->name('editPostPage');
    
    Route::get('login', 'AuthController@login')->name('login');
    Route::post('login', 'AuthController@authenticate')->name('loginP');
    
    Route::get('blog/{default}','BlogController@blog')->name('blog-name');
    Route::get('entry/{alpha}','BlogController@entry')->where(['alpha' => 'a'])->name('blog-entry');
    Route::get('page/{integer}','BlogController@page')->name('blog-page')->where('integer', 'i');
    Route::get('post/{slug}','BlogController@post')->name('blog-slug')->where(['slug' => 's']);
    
    Route::get('dynamic/page/{integer}','BlogController@dynamicPage')->name('dynamic-page')->where('integer', '[0-7]++');
    
    Route::get('dynamic/{id}/{name}/{id2}/{name2}', 'DynamicController@complex')->name('dynamic')->where(['id' => '[0-6]++', 'name' => 'a', 'id2' => 'i']);
    
    