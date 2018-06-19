<? $title = "Getting started"; ?>

<h1>Getting started</h1>
<p>Download the package using composer</p>
<pre class="prettyprint linenums:0">composer require GeordieJackson/router</pre>
<p>Import the router factory class into your project</p>
<pre class="prettyprint linenums:0">use GeordieJackson\Router\Classes\RouterFactory;</pre>

<p>To create an empty router use</p>

<pre class="prettyprint linenums:0">$router = RouterFactory::create();</pre>
<p>Or to create a router and load routes from files in a directory</p>

<pre class="prettyprint linenums:0">$router = RouterFactory::create( __DIR__ . '/path/to/your/routes/directory' );</pre>

<p>Register your dependency injection container</p>

<pre class="prettyprint linenums:0">$container = new YourContainer();<br><br>$router->setContainer($container);</pre>

<p><b>NOTE: </b>if you're not using a DI container, the router will automatically resolve your classes' dependencies for you, but this functionality is limited to <i>typehinted dependencies in class constructors only</i>.</p>