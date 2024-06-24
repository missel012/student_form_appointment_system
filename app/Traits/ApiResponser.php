<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponser
{
    /**
     * Build success response.
     *
     * @param string|array $data The data to be returned in the response
     * @param int $code The HTTP status code for the response (default is 200 OK)
     * @return Illuminate\Http\Response The JSON response with the provided data and status code
     */
    public function successResponse($data, $code = Response::HTTP_OK)
    {
        return response($data, $code)->header('Content-Type', 'application/json');
    }

    /**
     * Build valid response.
     *
     * @param string|array $data The data to be returned in the response
     * @param int $code The HTTP status code for the response (default is 200 OK)
     * @return Illuminate\Http\JsonResponse The JSON response with the provided data and status code
     */
    public function validResponse($data, $code = Response::HTTP_OK)
    {
        return response()->json(['data' => $data], $code);
    }

    /**
     * Build error response.
     *
     * @param string $message The error message to be returned in the response
     * @param int $code The HTTP status code for the response
     * @return Illuminate\Http\JsonResponse The JSON response with the error message and status code
     */
    public function errorResponse($message, $code)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    /**
     * Build error message response.
     *
     * @param string $message The error message to be returned in the response
     * @param int $code The HTTP status code for the response
     * @return Illuminate\Http\Response The JSON response with the provided error message and status code
     */
    public function errorMessage($message, $code)
    {
        return response($message, $code)->header('Content-Type', 'application/json');
    }
}

