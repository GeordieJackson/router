<h1>A simple router with Laravel-style syntax</h1>

`composer require GeordieJackson/router`

<h2>Remit</h2>

<p>For use in small to medium sized projects. It's provisioned to meet most requirements while avoiding unneccessary overhead in smaller applications.</p>

<h3>Features</h3>
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

<h3>Documentation</h3>
<p>Full documentation: <a href="http://johnjackson.me.uk/router" _target="blank">Router docs</a></p>