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



$router->get('/form-requests', ['as' => 'form-requests.index', 'uses' => 'FormRequestController@index']);
$router->post('/form-requests', ['as' => 'form-requests.store', 'uses' => 'FormRequestController@store']);
$router->get('/form-requests/{id}', ['as' => 'form-requests.show', 'uses' => 'FormRequestController@show']);
$router->put('/form-requests/{id}', ['as' => 'form-requests.update', 'uses' => 'FormRequestController@update']);
$router->patch('/form-requests/{id}', ['as' => 'form-requests.update.partial', 'uses' => 'FormRequestController@update']);
$router->delete('/form-requests/{id}', ['as' => 'form-requests.destroy', 'uses' => 'FormRequestController@destroy']);

//booking
$router->get('/redirect', 'GoogleCalendarController@redirectToGoogle');
$router->get('/oauth2callback', 'GoogleCalendarController@handleGoogleCallback');
$router->get('/api/calendar/events', 'GoogleCalendarController@fetchEvents');
$router->post('/book-appointment', ['as' => 'book-appointment', 'uses' => 'GoogleCalendarController@bookAppointment']);
$router->get('/show-appointment', ['as' => 'show-appointment', 'uses' => 'GoogleCalendarController@showAppointmentForm']);



// Payment Routes
$router->post('/payments', 'PaymentController@create');
$router->get('/payments/{paymentId}', 'PaymentController@retrieve');
$router->put('/payments/{paymentId}', 'PaymentController@update');
$router->delete('/payments/{paymentId}', 'PaymentController@delete');


// Routes for testing Paymongo API
$router->post('/payments', 'PaymentController@create');
$router->get('/paymongo/payment-intent', 'PaymentController@createPaymentIntent');