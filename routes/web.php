<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\GoogleCalendarController;

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/test', function () {
    return 'Routes are loaded!';
});


//form-request
$router->get('/form-requests', ['as' => 'form-requests.index', 'uses' => 'FormRequestController@index']);
$router->post('/form-requests', ['as' => 'form-requests.store', 'uses' => 'FormRequestController@store']);
$router->get('/form-requests/{id}', ['as' => 'form-requests.show', 'uses' => 'FormRequestController@show']);
$router->put('/form-requests/{id}', ['as' => 'form-requests.update', 'uses' => 'FormRequestController@update']);
$router->patch('/form-requests/{id}', ['as' => 'form-requests.update.partial', 'uses' => 'FormRequestController@update']);
$router->delete('/form-requests/{id}', ['as' => 'form-requests.destroy', 'uses' => 'FormRequestController@destroy']);

//booking
$router->get('/auth', 'GoogleCalendarController@redirectToGoogle');
$router->post('/oauth2callback', 'GoogleCalendarController@handleOAuthCallback');
$router->get('/events/{id}', ['uses' => 'GoogleCalendarController@viewEvent']);
$router->post('/events', ['uses' => 'GoogleCalendarController@createEvent']);
$router->put('/events/{id}', ['uses' => 'GoogleCalendarController@updateEvent']);
$router->patch('/events/{id}', ['uses' => 'GoogleCalendarController@updateEvent']);
$router->delete('/events/{id}', ['uses' => 'GoogleCalendarController@deleteEvent']);


// Payment Routes
$router->post('/payments', 'PaymentController@create');
$router->get('/payments/{paymentId}', 'PaymentController@retrieve');
$router->put('/payments/{paymentId}', 'PaymentController@update');
$router->delete('/payments/{paymentId}', 'PaymentController@delete');


// Routes for testing Paymongo API
$router->post('/payments', 'PaymentController@create');
$router->get('/paymongo/payment-intent', 'PaymentController@createPaymentIntent');

//sendgrid
$router->post('/send-email', 'MailController@sendTestEmail');
