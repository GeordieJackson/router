<h1>Middleware</h1>
<p>As this router is framework agnostic, it does not automatically run your route middleware; however, route middleware can be added to your route definitions and executed between the route matching and route dispatching processes. This way, you can handle your route middleware any way you choose.</p>
<h2>Adding middleware</h2>
<p>Middleware is added to routes by adding their names to the middleware method.</p>
<p>If you have middleware such as:
<pre>
    $middleware = [
        'auth' => AuthMiddleware::class,
        'isAdmin' => IsAdmin::class,
        'isModerator' => IsModerator::class,
    ];
</pre>
</p>
<p>They can be added to route definitions as:</p>
<pre class="prettyprint linenums:0">
    Route::get('users', 'UserController@index')->middleware('auth');
    Route::get('users', 'UserController@index')->middleware(['auth', 'isAdmin']);
</pre>
<p>NOTE: if adding more than one middleware name/key, they must be enrtered in an array.</p>
<p>Middleware can be added to REST/resource routing in the same way</p>
<pre class="prettyprint linenums:0">
    Route::resource('users', 'UserController')->middleware('auth');
    Route::resource('users', 'UserController')->middleware(['auth', 'isAdmin']);
</pre>
<p>The middleware will be added to <i>all</i> of the resource routes</p>
<p>Middleware can be added to route groups too by setting it in the group method's parameters</p>
<pre class="prettyprint linenums:0">
Route::group('dashboard', ['prefix' => 'admin', 'middleware' => ['auth', 'isModerator'] ], function() {
    Route::get('users', 'UserController@index')->middleware('isAdmin');
});
</pre>
<p>NOTE: Group middleware will be added before individually assigned middleware.</p>

<h3>Accessing and running middleware</h3>

<p>Middleware can be checked for on a routeInstance by using the <code class="code">hasMiddleware()</code> method and retrieved by the <code class="code">getMiddleware()</code> method</p>

<p>In order to run your route middleware, the basic methodology is:</p>
<ol>
    <li>Match the route and return it</li>
    <li>Check for middleware and execute it if present</li>
    <li>Dispatch the route</li>
</ol>
<p>Example</p>
<pre class="prettyprint linenums:0">
$route = $router->match($request->getRequestMethod(), $request->getPathInfo());

if($route->hasMiddleware()) {
    $yourMiddlewareHandler( $route->getMiddleware() );
}

$router->dispatchRoute($route)
</pre>