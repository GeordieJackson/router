<h1>Matching and dispatching</h1>
<p>The router consists of 2 main components:</p>
<ol>
    <li>Matcher - matches a stored url pattern to the incoming requestUri / path</li>
    <li>Dispatcher (optional) - executes the action stored on the route.</li>
</ol>

<p>To match to a stored route, the router requires the request method ('GET', 'POST', etc.) and the request path (e.g. 'about-us'). These can be extracted from <code>$_SERVER['REQUEST_METHOD']</code> and <code>$_SERVER['REQUEST_URI']</code> but if you're using a Request object it will be something like: <code>$request->getRequestMethod()</code> and <code>$request->getPathInfo()</code></p>
<h3>Matching</h3>
<p>To match a stored route the <code class="code">match</code> method is used.</p>
<pre class="prettyprint linenums:0">
$route = $router->match('GET', 'about-us');

or

$route = $router->match($request->getRequestMethod(), $request->getPathInfo());
</pre>
<p>will return a <code class="code">RouteInstance</code> object containing methods (getters and setters) for working with the route if required.</p>
<h3>Dispatching</h3>
<p>If you don't need to work with a route and simply want its action to be called then use the <code class="code">dispatch</code> method in place of <code class="code">match</code>. For example:</p>
<pre class="prettyprint linenums:0">
$route = $router->dispatch($request->getRequestMethod(), $request->getPathInfo());
</pre>
<p>Will match the route, inspect the <code class="code">RouteInstance</code>'s action (i.e. a callback or controller action) and execute the action automatically.</p>

<h3>Matching and dispatching</h3>
<p>If you want to work with a route before it's dispatched, e.g. you want to run some middleware stored in the route, then the basic workflow is:</p>

<pre class="prettyprint linenums:0">
$route = $router->match($request->getRequestMethod(), $request->getPathInfo());

if($route->hasMiddleware()) {
    $yourMiddlewareHandler( $route->getMiddleware() );
}

$router->dispatchRoute($route)
</pre>

<p>Note that the dispatch method is now <code class="code">dispatchRoute()</code> rather than <code class="code">dispatch()</code> as the route has already been matched and returned.</p>