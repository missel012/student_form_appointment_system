<?php

namespace App\Http\Controllers; // Declare the namespace for the Controllers

use Illuminate\Http\Request; // Import the Request class from Illuminate\Http
use App\Services\FormRequestService; // Import the FormRequestService class from App\Services

class FormRequestController extends Controller // Define the FormRequestController class extending the base Controller class
{
    protected $formRequestService; // Declare a protected property for the FormRequestService

    public function __construct(FormRequestService $formRequestService) // Constructor method to initialize the FormRequestService
    {
        $this->formRequestService = $formRequestService; // Assign the injected FormRequestService instance to the property
    }

    public function index() // Method to fetch all form requests
    {
        $formRequests = $this->formRequestService->getAllFormRequests(); // Retrieve all form requests using the service
        return response()->json($formRequests); // Return a JSON response with the form requests
    }

    public function store(Request $request) // Method to store a new form request
    {
        $result = $this->formRequestService->createFormRequest($request->all()); // Create a new form request using the request data

        if (isset($result['errors'])) { // Check if there are any validation errors
            return response()->json(['errors' => $result['errors']], $result['status']); // Return JSON response with errors and status code
        }

        return response()->json($result['formRequest'], $result['status']); // Return JSON response with the created form request and status code
    }

    public function show($id) // Method to fetch a specific form request by ID
    {
        $formRequest = $this->formRequestService->getFormRequestById($id); // Retrieve the form request by ID using the service

        if (!$formRequest) { // If form request is not found, return a 404 error response
            return response()->json(['error' => 'Form Request not found'], 404);
        }

        return response()->json($formRequest); // Return JSON response with the form request
    }

    public function update(Request $request, $id) // Method to update a form request by ID
    {
        $result = $this->formRequestService->updateFormRequest($id, $request->all()); // Update the form request using the request data

        if (isset($result['errors'])) { // Check if there are any validation errors
            return response()->json(['errors' => $result['errors']], $result['status']); // Return JSON response with errors and status code
        }

        if (isset($result['error'])) { // Check if there is any other specific error
            return response()->json(['error' => $result['error']], $result['status']); // Return JSON response with the error and status code
        }

        return response()->json($result['formRequest'], $result['status']); // Return JSON response with the updated form request and status code
    }

    public function destroy($id) // Method to delete a form request by ID
    {
        $result = $this->formRequestService->deleteFormRequest($id); // Delete the form request by ID using the service

        if (isset($result['error'])) { // Check if there is any error during deletion
            return response()->json(['error' => $result['error']], $result['status']); // Return JSON response with the error and status code
        }

        return response()->json(null, $result['status']); // Return a JSON response with null (indicating successful deletion) and status code
    }
}
