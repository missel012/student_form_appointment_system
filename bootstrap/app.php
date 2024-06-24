<?php
use Illuminate\Session\SessionManager;
use Illuminate\Session\Middleware\StartSession;

// Import necessary classes. SessionManager is for managing sessions, StartSession is middleware to start sessions.

require_once __DIR__.'/../vendor/autoload.php';

// Include the Composer autoload file to load all dependencies.

(new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
    dirname(__DIR__)
))->bootstrap();

// Load environment variables from the .env file.

date_default_timezone_set(env('APP_TIMEZONE', 'UTC'));

// Set the default timezone, either from the environment variable APP_TIMEZONE or default to UTC.

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    dirname(__DIR__)
);

// Create a new Lumen application instance. This is the main IoC container and router for the application.

$app->withFacades();

// Enable facades for the application.

$app->withEloquent();

// Enable Eloquent ORM for the application.

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

// Register a singleton binding for the exception handler.

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

// Register a singleton binding for the console kernel.

$app->singleton('session', function ($app) {
    return $app->loadComponent('session', Illuminate\Session\SessionServiceProvider::class, SessionManager::class);
});

// Register a singleton binding for the session manager, loading the session component.

$app->singleton('App\PaymongoAPI', function ($app) {
    return new App\PaymongoAPI(new GuzzleHttp\Client());
});

// Register a singleton binding for the PaymongoAPI, initializing it with a Guzzle HTTP client.

$app->singleton(App\Services\MailService::class, function ($app) {
    return new App\Services\MailService(new SendGrid(env('SENDGRID_API_KEY')));
});

// Register a singleton binding for the MailService, initializing it with a SendGrid client using the API key from the environment.

$app->singleton('App\Services\DocumentTypeService', function ($app) {
    return new \App\Services\DocumentTypeService();
});

// Register a singleton binding for the DocumentTypeService.

$app->singleton('App\Services\FormRequestService', function ($app) {
    return new \App\Services\FormRequestService();
});

// Register a singleton binding for the FormRequestService.

$app->singleton(\App\Services\AuthService::class, function ($app) {
    return new \App\Services\AuthService();
});

// Register a singleton binding for the AuthService.

$app->middleware([
    StartSession::class,
]);

// Register the StartSession middleware globally.

/*
|--------------------------------------------------------------------------
| Register Config Files
|--------------------------------------------------------------------------
|
| Now we will register the "app" configuration file. If the file exists in
| your configuration directory it will be loaded; otherwise, we'll load
| the default version. You may register other files below as needed.
|
*/
$app->configure('app');

// Register the "app" configuration file.

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/
$app->middleware([
    Illuminate\Session\Middleware\StartSession::class,
]);

// Register the StartSession middleware globally again (duplicated, may be redundant).

$app->routeMiddleware([
    'auth' => App\Http\Middleware\Authenticate::class,
    'client.credentials' => Tymon\JWTAuth\Providers\LumenServiceProvider::class,
]);

// Register route-specific middleware for authentication and JWT client credentials.

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

$app->register(App\Providers\GoogleCalendarServiceProvider::class);
$app->register(App\Providers\SendGridServiceProvider::class);
$app->register(App\Providers\AppServiceProvider::class);
$app->register(\Collective\Html\HtmlServiceProvider::class);
$app->register(Tymon\JWTAuth\Providers\LumenServiceProvider::class);

// Register various service providers for Google Calendar, SendGrid, application services, HTML forms, and JWT authentication.

//$app->register(App\Providers\AuthServiceProvider::class);
//$app->register(App\Providers\EventServiceProvider::class);

// Uncomment to register AuthServiceProvider and EventServiceProvider if needed.

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

date_default_timezone_set('UTC'); // or your preferred timezone

// Set the default timezone.

class_alias(Collective\Html\FormFacade::class, 'Form');
class_alias(Collective\Html\HtmlFacade::class, 'Html');

// Create class aliases for the HTML and Form facades.

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set error reporting to display all errors.

$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__.'/../routes/web.php';
});

// Load the application routes, grouping them under the App\Http\Controllers namespace.

return $app;

// Return the application instance.
