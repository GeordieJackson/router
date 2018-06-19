<h1>Route constraints</h1>
<p>When creating routes using wildcard matching, it's possible to add constraints using the optional <code class="code">where()</code> method with a regular expression.</p>
<pre class="prettyprint linenums:0">
Route::get('posts/{id}', 'PostController@show')->where('id', '[0-9]+');
</pre>
<p>In this example the wildcard parameter 'id' will be restricted to integers. If a non-integer value is used, the match will fail.</p>
<p>More than one constraint can be stipulated</p>
<pre class="prettyprint linenums:0">
Route::get('author/{id}/posts/{category}', 'PostController@list')->where(['id' => '[0-9]+', 'category' => '[a-z0-9-]+' ]);
</pre>
<p>In this example the wildcard parameter 'id' will be restricted to integers and 'category' to lowercase letters, numbers and dashes (ideal for a slug).</p>
<h3>Presets</h3>
<p>There are a few preset shortcuts available. If you want to restrict a parameter to integers it's also possbile to use:</p>
<pre class="prettyprint linenums:0">
Route::get('posts/{id}', 'PostController@show')->where('id', 'i');
</pre>
<p>The <i>i</i> being the shortcut for integers</p>
<p>The shortcuts are:</p>
<ul>
    <li>i = '[0-9]+' // One or more integers</li>
    <li>s = '[a-z0-9-]+' // lowercase letters, numbers and dashes. For url slugs</li>
    <li>a = '[0-9A-Za-z]+' // Alphanumeric</li>
    <li>d = '[0-9A-Za-z-_]+' // Alphanumeric plus dashes and underscores. This is the default matching constraint.</li>
</ul>

