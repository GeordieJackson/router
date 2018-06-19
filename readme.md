<h1>A simple router with Laravel-style syntax</h1>

`composer require GeordieJackson/router`

<h2>Remit</h2>

<p>For use in small to medium sized projects. It's provisioned to meet most requirements while avoiding unneccessary overhead in smaller applications.</p>

<h3>Features</h3>
<ul>
<li>Basic routing. Match the requestUri/Path to: 'get', 'post', 'put', 'patch', 'update' and 'delete' designated routes.</li>
<li>Supports Laravel-style syntax. e.g. Route::get('/', 'PageController@index')->name('home');</li>
<li>Map routes to callbacks.</li>
<li>Map routes to controller + action (ControllerName@action).</li>
<li>Wildcards for dynamic matching. e.g. Route::get('post/{slug}', 'PostController@show');</li>
<li>Add regex constraints to routes. e.g. Route::get('user/{id}', 'UserController@show')->where('id', '[0-9]+');</li>
<li>Load routes from a directory - separate routes into several files.
    * Named routes - with reverse lookup.</li>
<li>Route groups - with assignable prefix, namespace, name prefix and middleware.</li>
<li>RESTful route creation from a single command. e.g. Route::resource('PostController').</li>
</ul>

###Requirements

PHP 7.1 or newer

###Recommended

A DI container for dependency management (Pimple, Aura, DI, Phalcon, etc.) 

<h3>Documentation</h3>
<p>Full documentation: <a href="http://johnjackson.me.uk/router" _target="_blank">Router docs</a></p>