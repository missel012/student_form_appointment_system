<?php

/** @var \Laravel\Lumen\Routing\Router $router */
/** @var \Laravel\Lumen\Routing\Router $router */
// Define a type hint for the router instance, specifying it as a Lumen Router.

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\GoogleCalendarController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\PaymentLinkController;

// Import necessary controller classes.

$router->get('/', function () use ($router) {
    return "Welcome to Student Forms Appointment System";
});

// Define a route for the root URL, returning a welcome message.

$router->get('/test', function () {
    return 'Routes are loaded!';
});

// Define a test route to verify that routes are loaded.

Route::group([

    'prefix' => 'user'

], function ($router) {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::get('profile/{id}', 'AuthController@getUserProfileById');
});

// Group routes under the prefix 'user' for user-related actions such as register, login, logout, refresh, and getting a user profile.

$router->group(['middleware' => 'auth'], function ($router) {

    // Define a group of routes that require authentication using the 'auth' middleware.

    // Retrieve Documents
    $router->get('/document-types', 'DocumentTypeController@index');
    $router->get('/document-types/{id}', 'DocumentTypeController@show');
    // Define routes for retrieving document types and specific document type by ID.

    // Form Request routes
    $router->get('/form-requests', ['as' => 'form-requests.index', 'uses' => 'FormRequestController@index']);
    $router->post('/form-requests', ['as' => 'form-requests.store', 'uses' => 'FormRequestController@store']);
    $router->get('/form-requests/{id}', ['as' => 'form-requests.show', 'uses' => 'FormRequestController@show']);
    $router->put('/form-requests/{id}', ['as' => 'form-requests.update', 'uses' => 'FormRequestController@update']);
    $router->patch('/form-requests/{id}', ['as' => 'form-requests.update.partial', 'uses' => 'FormRequestController@update']);
    $router->delete('/form-requests/{id}', ['as' => 'form-requests.destroy', 'uses' => 'FormRequestController@destroy']);
    // Define CRUD routes for form requests with route names and controller methods.

    // Booking routes
    $router->get('/events/{id}', ['uses' => 'GoogleCalendarController@viewEvent']);
    $router->post('/events', ['uses' => 'GoogleCalendarController@createEvent']);
    $router->put('/events/{id}', ['uses' => 'GoogleCalendarController@updateEvent']);
    $router->patch('/events/{id}', ['uses' => 'GoogleCalendarController@updateEvent']);
    $router->delete('/events/{id}', ['uses' => 'GoogleCalendarController@deleteEvent']);
    // Define routes for managing events in Google Calendar with various HTTP methods.

    // Payment routes
    $router->post('/payments/create', 'PaymentLinkController@createLink');
    $router->get('/payments/{transactionId}', 'PaymentLinkController@viewPayment');
    // Define routes for creating payment links and viewing payment details.

    // Sendgrid route
    $router->post('/email-send', 'MailController@sendEmail');
    // Define a route for sending emails using SendGrid.
});

// Define routes for Google OAuth authentication and callback
$router->get('/auth', 'GoogleCalendarController@redirectToGoogle');
// Route for redirecting to Google's OAuth page.

$router->post('/oauth2callback', 'GoogleCalendarController@handleOAuthCallback');
// Route for handling the OAuth callback from Google.
