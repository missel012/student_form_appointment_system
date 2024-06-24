<?php

namespace App\Services;

use App\Models\FormsRequest; // Import the FormsRequest model
use Illuminate\Support\Facades\Validator; // Import Validator class for validation

class FormRequestService
{
    /**
     * Retrieve all form requests from the database.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Models\FormsRequest[]  Collection of all form requests
     */
    public function getAllFormRequests()
    {
        return FormsRequest::all(); // Retrieve all form requests using Eloquent ORM
    }

    /**
     * Create a new form request in the database.
     *
     * @param  array  $data  Data for creating the form request
     * @return array  Array containing 'formRequest' if successful, or 'errors' if validation fails
     */
    public function createFormRequest($data)
    {
        // Validate the incoming data
        $validator = Validator::make($data, [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'student_id' => 'required|string',
            'document_type_id' => 'required|exists:document_types,id',
        ]);

        // If validation fails, return errors and status code 422 (Unprocessable Entity)
        if ($validator->fails()) {
            return ['errors' => $validator->errors(), 'status' => 422];
        }

        // Create a new form request record
        $formRequest = FormsRequest::create($data);

        // Return success response with created form request and status code 201 (Created)
        return ['formRequest' => $formRequest, 'status' => 201];
    }

    /**
     * Retrieve a form request by its ID from the database.
     *
     * @param  int  $id  Form request ID
     * @return \App\Models\FormsRequest|null  FormsRequest instance if found, otherwise null
     */
    public function getFormRequestById($id)
    {
        return FormsRequest::find($id); // Retrieve a form request by ID using Eloquent ORM
    }

    /**
     * Update an existing form request in the database.
     *
     * @param  int  $id  Form request ID
     * @param  array  $data  Data for updating the form request
     * @return array  Array containing 'formRequest' if successful, 'error' if form request not found,
     *                or 'errors' if validation fails
     */
    public function updateFormRequest($id, $data)
    {
        // Validate the incoming data
        $validator = Validator::make($data, [
            'first_name' => 'sometimes|required|string',
            'last_name' => 'sometimes|required|string',
            'student_id' => 'sometimes|required|string',
            'document_type_id' => 'sometimes|required|exists:document_types,id',
        ]);

        // If validation fails, return errors and status code 422 (Unprocessable Entity)
        if ($validator->fails()) {
            return ['errors' => $validator->errors(), 'status' => 422];
        }

        // Find the form request by ID
        $formRequest = FormsRequest::find($id);
        if (!$formRequest) {
            return ['error' => 'Form Request not found', 'status' => 404];
        }

        // Update the form request with the new data
        $formRequest->update($data);

        // Return success response with updated form request and status code 200 (OK)
        return ['formRequest' => $formRequest, 'status' => 200];
    }

    /**
     * Delete a form request from the database.
     *
     * @param  int  $id  Form request ID
     * @return array  Array containing status code 204 (No Content) if successful,
     *                or 'error' if form request not found
     */
    public function deleteFormRequest($id)
    {
        // Find the form request by ID
        $formRequest = FormsRequest::find($id);
        if (!$formRequest) {
            return ['error' => 'Form Request not found', 'status' => 404];
        }

        // Delete the form request
        $formRequest->delete();

        // Return success response with status code 204 (No Content)
        return ['status' => 204];
    }
}

