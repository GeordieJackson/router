<h1>Route groups</h1>
<p>Route groups can be used to assign common attributes to related routes. An example would be configuring routes for a dashboard in an admin section.</p>

<p>The <code class="code">group</code>method is used and has the following structure.</p>
<pre class="prettyprint linenums:0">
Route::group([parameters array], function () {
    Route definitions
});
</pre>
<p>The dashboard example will look something like</p>
<pre class="prettyprint linenums:0">
Route::group(['prefix' => 'dashboard', 'name' => 'admin.'], function () {
    Route::get('users', 'UserController@index')->name('users.index');
    Route::get('users/{user}', 'UserController@show')->name('users.show');
});
</pre>
<p>RESTful resource routes can also be placed within groups. e.g.</p>
<pre class="prettyprint linenums:0">
Route::group(['prefix' => 'dashboard', 'name' => 'admin.'], function () {
    Route::resource('posts', 'PostController');
});
</pre>
<h3>How it works</h3>
The group parameters will be <i>prepended</i> to the corresponding parameters in the route definitions. For example, in the 'users' example above, the 2 routes would have their paths set to <code class="code">dashboard/users</code> and <code class="code">dashboard/users/{user}</code> and their names would be set to <code class="code">admin.users.index</code> and <code class="code">admin.users.show</code>
<p><b>TIP: </b>Notice the dot at the end of the group name parameter. Remember to add this if you want your routes to conform to dot notation.</p>
<h2>Group parameters</h2>
<p>The parameters that can be added to the <i>parameters array</i> are:</p>
<ul>
    <li>
        <b>Prefix</b>
        <pre class="prettyprint linenums:0">
Route::group(['prefix' => 'dashboard'], function () {
    Route::get('users', 'UserController@index')->name('users.index');
});
</pre>
        <p>This prepends the prefix to the route's path. In this instance, the path would be set to <code class="code">dashboard/users</code></p>
    </li>
    <li>
        <b>Name</b>
        <pre class="prettyprint linenums:0">
Route::group(['name' => 'admin.'], function () {
    Route::get('users', 'UserController@index')->name('users.index');
});
</pre>
        <p>This prepends the group name to the route's name. In this instance the route would be named <code class="code">admin.users.index</code></p>
    </li>
    <li>
        <b>Namespace</b>
        <pre class="prettyprint linenums:0">
Route::group(['namespace' => 'VendorName\Project\Path'], function () {
    Route::get('users', 'UserController@index')->name('users.index');
});
</pre>
        <p>This prepends the namespace to the controller. In this instance the controller would be referenced by <code class="code">VendorName\Project\Path\UserController</code></p>
        <p>See more on the <a href="/namespacing">namespacing</a> page</p>
    </li>
    <li>
        <b>Middleware</b>
        <pre class="prettyprint linenums:0">
Route::group(['middleware' => ['auth', 'perms']], function () {
    Route::get('users', 'UserController@index')->name('users.index')->middleware('Csrf');
});
</pre>
        <p>The names of any middleware you want to call on the route can be added to the group as an array. They will be prepended to any middleware stored on individual routes (i.e. they will be called first).</p>
        <p>In this instance, middleware would be stored as <code class="code">['auth', 'perms', 'Csrf']</code></p>
    </li>
    <p>See more on the <a href="/middleware">middleware</a> page</p>
</ul>
