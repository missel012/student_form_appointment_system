<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\GoogleCalendarController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\PaymentLinkController;

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/test', function () {
    return 'Routes are loaded!';
});

Route::group([

    'prefix' => 'user'

], function ($router) {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::get('profile/{id}', 'AuthController@getUserProfileById');


});

$router->group(['middleware' => 'auth'], function ($router) {

   
    //Retrieve Documents
    $router->get('/document-types', 'DocumentTypeController@index');
    $router->get('/document-types/{id}', 'DocumentTypeController@show');

    // Form Request routes
    $router->get('/form-requests', ['as' => 'form-requests.index', 'uses' => 'FormRequestController@index']);
    $router->post('/form-requests', ['as' => 'form-requests.store', 'uses' => 'FormRequestController@store']);
    $router->get('/form-requests/{id}', ['as' => 'form-requests.show', 'uses' => 'FormRequestController@show']);
    $router->put('/form-requests/{id}', ['as' => 'form-requests.update', 'uses' => 'FormRequestController@update']);
    $router->patch('/form-requests/{id}', ['as' => 'form-requests.update.partial', 'uses' => 'FormRequestController@update']);
    $router->delete('/form-requests/{id}', ['as' => 'form-requests.destroy', 'uses' => 'FormRequestController@destroy']);

    // Booking routes
    $router->get('/events/{id}', ['uses' => 'GoogleCalendarController@viewEvent']);
    $router->post('/events', ['uses' => 'GoogleCalendarController@createEvent']);
    $router->put('/events/{id}', ['uses' => 'GoogleCalendarController@updateEvent']);
    $router->patch('/events/{id}', ['uses' => 'GoogleCalendarController@updateEvent']);
    $router->delete('/events/{id}', ['uses' => 'GoogleCalendarController@deleteEvent']);

    // Payment route
    $router->post('/payments/create', 'PaymentLinkController@createLink');
    $router->get('/payments/{transactionId}', 'PaymentLinkController@viewPayment');
        

    // Sendgrid route
    $router->post('/email-send', 'MailController@sendEmail');

});

//google auth
$router->get('/auth', 'GoogleCalendarController@redirectToGoogle');
$router->post('/oauth2callback', 'GoogleCalendarController@handleOAuthCallback');

