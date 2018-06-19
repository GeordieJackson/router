#A simple router with Laravel-style syntax

##Getting started
`composer require GeordieJackson/router`

###Remit

For use in small to medium sized projects. It's provisioned to meet most requirements while avoiding unneccessary overhead in smaller applications.

###Features
* Basic routing. Match the requestUri/Path to: 'get', 'post', 'put', 'patch', 'update' and 'delete' designated routes.
* Supports Laravel-style syntax. e.g. Route::get('/', 'PageController@index')->name('home');
* Map routes to callbacks.
* Map routes to controller + action (ControllerName@action).
* Wildcards for dynamic matching. e.g. Route::get('post/{slug}', 'PostController@show');
* Add regex constraints to routes. e.g. Route::get('user/{id}', 'UserController@show')->where('id', '[0-9]+');
* Load routes from a directory - separate routes into several files.
* Named routes - with reverse lookup.
* Route groups - with assignable prefix, namespace, name prefix and middleware.
* RESTful route creation from a single command. e.g. Route::resource('PostController').

###Requirements

PHP 7.1 or newer

###Recommended

A DI container for dependency management (Pimple, Aura, DI, Phalcon, etc.) 

###Usage

    use GeordieJackson\Router\Classes\RouterFactory;

Create a router.

    $router = RouterFactory::create();

Create a router and load routes from files in a directory.

    $router = RouterFactory::create( __DIR__ . '/path/to/your/routes/directory' );

##Registering your dependency injection container

    $container = new YourContainer(); // Or however yours is set up
    
    $router->setContainer($container);

####Adding routes

Typing them in.

    $router->get('authors', 'AuthorController@index');
    $router->get('authors/{author}', 'AuthorController@show');

or Laravel style

    use GeordieJackson\Router\Classes\Route;
    
    Route::get('tags', 'TagController@index');
    Route::get('tags/{tag}', 'TagController@show');

#####Loading from a directory.

Create your routing directory i.e. path/to/routing/directory, then place as many files as you wish in it. e.g.
 - api.php
 - web.php
 
 The router will scan them all and load the routes automatically.
 
 Each file should contain: 
 
     use GeordieJackson\Router\Classes\Route;

and your route definitions. e.g.

    Route::get('authors', 'AuthorController@index');
    Route::get('authors/{author}', 'AuthorController@show');
        
    Route::group(['prefix' => 'dashboard', 'namespace' => 'Admin', 'name' => 'admin.', 'middleware' => ['auth'], function () {
        Route::get('/', 'DashboardController@index');
        Route::resource('user', 'UserController')->middleware('isAdmin');
        Route::resource('post', 'PostController')->middleware('isModerator');
    });

###Types of routing and routing parameters

####Anatomy of a route

    Route::verb('path', 'Action', ['Optional Parameters']);

verb = 'get', 'post', 'put', 'patch', 'update' or 'delete';

action = a controller name + method (e.g. UserController@update) or a callback.

#####Examples

    Route::get('about', 'PageController@about')
    
    Route::get('hello/{name}', function ($name) {
        return "Hello $name";
    });
    
#####Named routes

Routes can be named. This is useful for reverse lookups and Url generation. e.g.

    Route::get('about-us', 'PageController@about')->name('about');

If we now use the reverse lookup function:

    $router->url('about'); // Returns 'about-us'
    
NOTE1: RESTful routes ('resource' routes) are named automatically so they don't need to have a name set - although it is possible to do so if required.

NOTE2: Using 'name' as a 'group' parameter will *prepend* the name onto any other that is set. 

###Namespacing

If you're using a DI container and it's registered with the router you probably won't need to use namespacing as your container will be handling it all; however, your classes' namespaces can be added to your routes if required.

#####Setting a default namespace 

Set up your router and immediately set the default namespace.

    $router = RouterFactory::create();
    $router->setDefaultNamespace('Your\\Default\\Namespace');

This will then be placed in front of all your controller names. e.g.

    Route::get('about', 'PageController@about');

Will be stored (and called) as:     'Your\Default\Namespace\PageController';

#####Individual namespacing

A namespace can be set on individual routes, resource routes (RESTful routes) and route groups. e.g.

    Route::get('about', 'PageController@about')->namespace('Non\\Standard\\Namespace');
    
    Route::resource('users', 'UserController')->namespace('Non\\Standard\\Namespace');
    
    Route::group(['namespace' => 'Admin'], function () {
        Route::get('/', 'DashboardController@index');
        Route::resource('user', 'UserController');
        Route::resource('post', 'PostController');
    });










