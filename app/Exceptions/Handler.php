<?php

namespace App\Exceptions; // Declare the namespace for the Exceptions

use App\Traits\ApiResponser; // Import the ApiResponser trait
use Illuminate\Http\Request; // Import the Request class from Illuminate\Http
use Illuminate\Http\Response; // Import the Response class from Illuminate\Http

use GuzzleHttp\Exception\ClientException; // Import the ClientException class from GuzzleHttp\Exception

use Illuminate\Auth\Access\AuthorizationException; // Import the AuthorizationException class from Illuminate\Auth\Access
use Illuminate\Database\Eloquent\ModelNotFoundException; // Import the ModelNotFoundException class from Illuminate\Database\Eloquent
use Illuminate\Validation\ValidationException; // Import the ValidationException class from Illuminate\Validation
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler; // Import the ExceptionHandler class from Laravel\Lumen\Exceptions and alias it as ExceptionHandler
use Symfony\Component\HttpKernel\Exception\HttpException; // Import the HttpException class from Symfony\Component\HttpKernel\Exception
use Throwable; // Import the Throwable interface

class Handler extends ExceptionHandler // Define the Handler class extending the ExceptionHandler class
{

    use ApiResponser; // Use the ApiResponser trait within the Handler class

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class, // Do not report AuthorizationException
        HttpException::class, // Do not report HttpException
        ModelNotFoundException::class, // Do not report ModelNotFoundException
        ValidationException::class, // Do not report ValidationException
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception) // Method to report or log an exception
    {
        parent::report($exception); // Call the parent class's report method
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception) // Method to render an exception into an HTTP response
    {
        // return parent::render($request, $exception);

        // http not found
        if ($exception instanceof HTTPException) { // Check if the exception is an instance of HTTPException
            $code = $exception->getStatusCode(); // Get the status code of the exception
            $message = Response::$statusTexts[$code]; // Get the status text based on the status code

            return $this->errorResponse($message, $code); // Return an error response with the message and status code
        }

        // instance not found
        if ($exception instanceof ModelNotFoundException) { // Check if the exception is an instance of ModelNotFoundException
            $model = strtolower(class_basename($exception->getModel())); // Get the model name and convert it to lowercase

            return $this->errorResponse("Does not exist any instance of {$model} with the given id", Response::HTTP_NOT_FOUND); // Return an error response with a custom message and 404 status code
        }

        // validation exception
        if ($exception instanceof ValidationException) { // Check if the exception is an instance of ValidationException
            $errors = $exception->validator->errors()->getMessages(); // Get the validation error messages

            return $this->errorResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY); // Return an error response with the error messages and 422 status code
        }

        // access to forbidden
        if ($exception instanceof AuthorizationException) { // Check if the exception is an instance of AuthorizationException
            return $this->errorResponse($exception->getMessage(), Response::HTTP_FORBIDDEN); // Return an error response with the exception message and 403 status code
        }

        // unauthorized access
        if ($exception instanceof AuthenticationException) { // Check if the exception is an instance of AuthenticationException
            return $this->errorResponse($exception->getMessage(), Response::HTTP_UNAUTHORIZED); // Return an error response with the exception message and 401 status code
        }

        if ($exception instanceof ClientException) { // Check if the exception is an instance of ClientException
            $message = $exception->getResponse()->getBody(); // Get the response body of the exception
            $code = $exception->getCode(); // Get the status code of the exception

            return $this->errorMessage($message, 200); // Return an error message with the message and status code 200
        }

        // if you are running in development environment
        if (env('APP_DEBUG', false)) { // Check if the application is running in debug mode
            return parent::render($request, $exception); // Return the parent class's render method
        }

        return $this->errorResponse('Unauthorized error. Try later', Response::HTTP_INTERNAL_SERVER_ERROR); // Return a generic error response with a custom message and 500 status code
    }
}
