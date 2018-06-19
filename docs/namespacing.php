
<h1>Namespacing</h1>
<p><b>NOTE: </b>If you're using a DI container and it's registered with the router you probably won't need to use namespacing as your container will be handling it all; however, your classes' namespaces can be added to your routes if required.</p>
<p>Namespaces can be set at 3 levels:</p>
<ol>
    <li>
        <b>Default</b>
        <p>This will apply to all routes stored in the router (with one exception - see absolute namespaces below)</p>
        <p>The default namespace can be reset at any time. See 'working with files' below.</p>
    </li>
    <li><b>Group</b>
        <p>Routes that are grouped can share a common namespace</p>
    </li>
    <li><b>Individual</b>
    <p>Namespaces can also be applied to individual routes and RESTful resource routes.</p>
    </li>
</ol>
<h3>Order of precedence</h3>
<p>Namespaces will always be applied in the following order: <code class="code">Default</code> \ <code class="code">Group</code> \ <code class="code">Individual</code></p>
<p>Any combination of namespace levels can be set and they will always be applied in that order. Empty values will be skipped.</p>
<h3>Setting a default namespace</h3>
<p>The default namespace will be prepended to <i>all</i> of your controllers. This makes it very useful when all of your controllers are under a common namespace.</p>
<p>Set up your router and set the default namespace <i>before</i> you add or load routes. Loading routes from a directory is optional.</p>
<pre class="prettyprint linenums:0">
$router = RouterFactory::create();
$router->setDefaultNamespace('Your\\Default\\Namespace');
$router->load( __DIR__ . '/path/to/your/routes/directory'); // Optional

Or chain the methods:

$router = RouterFactory::create()
            ->setDefaultNamespace('Your\\Default\\Namespace')
            ->load( __DIR__ . '/path/to/your/routes/directory')
        ;
</pre>

<p>This default namespace will then be placed in front of all your controller names. e.g.</p>
<pre class="prettyprint linenums:0">
Route::get('about', 'PageController@about');
</pre>
<p>Will be referenced as: <code class="code">Your\Default\Namespace\PageController</code></p>

<h3>Group namespacing</h3>

<p>A Namespace can be applied to groups by setting the name in the group's parameters.</p>
<pre class="prettyprint linenums:0">
Route::group(['namespace' => 'Admin'], function () {
    Route::get('/', 'DashboardController@index');
});
</pre>
<p>All routes defined within the group will have their namespace set to <code class="code">Admin</code></p>

<h3>Individual namespacing</h3>
<p>A namespace can be set on individual routes and RESTful / resource routes (a collection of individual routes)</p>
<pre class="prettyprint linenums:0">
Route::get('pages', 'PageController@index')->namespace('Page\\Namespace');
Route::resource('users', 'UserController')->namespace('User\\Namespace');
</pre>
<h3>Combining namespaces</h3>
<p>Namespaces can be assigned at more than one level</p>
<pre class="prettyprint linenums:0">
Route::group(['namespace' => 'Admin'], function () {
    Route::resource('users', 'UserController')->namespace('User\\Namespace');
});
</pre>
<p>And the namespaces will be combined in order of precedence (see above). In this example, the resource routes would all have the namespace <code class="code">Admin\User\Namespace</code> or <code class="code">Your\Default\Namespace\Admin\User\Namespace</code> if default namespacing was also set up.</p>

<h3>Overriding namespacing - absolute namespaces</h3>
<p>If you have a controller that is under a different namespace from the rest of your application, it can still be referenced by setting its namespace as an absolute value. i.e. by simply preceding its name with a backslash. e.g.</p>
<pre class="prettyprint linenums:0">
Route::get('different-page', 'DifferentController@index')->namespace('\\Different\\Namespace');
</pre>
<p>As the namespace begins with a backslash, it will be left untouched by the router. It doesn't matter whether default or route grouping is also set, they will simply be ignored.</p>
<h2>Working with files</h2>
<p>Although it makes sense to set the default namespace before addding any routes, the default namespace can be set and reset at any time. This makes it useful when working with differently namespaced sections of your application. e.g. your api and your web controllers may be under different namespaces.</p>
<p>If you place your api and web routes in different files in your routes directory, you can set the namespace in the file. e.g. in your routes/api.php file you could place above your route definitions:</p>
<pre class="prettyprint linenums:0">
Route::setDefaultNamespace('Your\\Api\\Controllers\\Namespace');
</pre>
<p>and at the top of your routes/web.php file you could place above your route definitions:</p>
<pre class="prettyprint linenums:0">
Route::setDefaultNamespace('Your\\Web\\Controllers\\Namespace');
</pre>
<p>and each section's controllers will be namespaced appropriately.</p>
<p><b>TIP: </b> Remember that the default namespace will remain set to the last value you set it to. You may need to reset it, or set it to null, if you subsequently add more routes in another file or manually.</p>
