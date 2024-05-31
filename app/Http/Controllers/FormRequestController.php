<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormsRequest;
use Illuminate\Support\Facades\Validator;

class FormRequestController extends Controller
{
    // Fetch all form requests
    public function index()
    {
        $formRequests = FormsRequest::all();
        return response()->json($formRequests);
    }

    // Store a new form request
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'student_id' => 'required|string',
            'document_type_id' => 'required|exists:document_types,id',
        ]);

        // If validation fails, return validation errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create and save a new form request
        $formRequest = FormsRequest::create($request->all());
        return response()->json($formRequest, 201);
    }

    // Show a specific form request
    public function show($id)
    {
        $formRequest = FormsRequest::find($id);
        if (!$formRequest) {
            return response()->json(['error' => 'Form Request not found'], 404);
        }
        return response()->json($formRequest);
    }

    // Update an existing form request
    public function update(Request $request, $id)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|required|string',
            'last_name' => 'sometimes|required|string',
            'student_id' => 'sometimes|required|string',
            'document_type_id' => 'sometimes|required|exists:document_types,id',
            'status' => 'sometimes|required|string|in:pending,approved,rejected',
        ]);

        // If validation fails, return validation errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Find the form request by ID and update it
        $formRequest = FormsRequest::find($id);
        if (!$formRequest) {
            return response()->json(['error' => 'Form Request not found'], 404);
        }

        $formRequest->update($request->all());
        return response()->json($formRequest, 200);
    }

    // Delete a form request
    public function destroy($id)
    {
        $formRequest = FormsRequest::find($id);
        if (!$formRequest) {
            return response()->json(['error' => 'Form Request not found'], 404);
        }
        $formRequest->delete();
        return response()->json(null, 204);
    }
}
