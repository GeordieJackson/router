
<h1>Background route information</h1>

<h3>Anatomy of a route</h3>

<pre class="prettyprint linenums:0">Route::verb('path', 'Action', ['Optional Parameters']);</pre>
<p>Where:</p>
<p>verb = 'get', 'post', 'put', 'patch', 'update' or 'delete';</p>
<p>path: the url/path you want to match against.</p>
<p>action = a controller name + method (e.g. UserController@update) or a callback.</p>
<p>parameters = user supplied variables in array format</p>

<h3>Examples</h3>
<p>This route will match a 'GET' request where the url path is 'about-us' and it maps to the <code class="code">PageController->about()</code> method.</p>
<pre class="prettyprint linenums:0">
Route::get('about-us', 'PageController@about')
</pre>

<p>This route will match a 'GET' request where the url is hello/name (where 'name' is any string) and the callback function will return "hello, name" when executed.</p>

<pre class="prettyprint linenums:0">
Route::get('hello/{name}', function ($name) {
    return "Hello $name";
});
</pre>
