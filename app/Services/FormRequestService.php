<?php

namespace App\Services;

use App\Models\FormsRequest;
use Illuminate\Support\Facades\Validator;

class FormRequestService
{
    public function getAllFormRequests()
    {
        return FormsRequest::all();
    }

    public function createFormRequest($data)
    {
        $validator = Validator::make($data, [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'student_id' => 'required|string',
            'document_type_id' => 'required|exists:document_types,id',
        ]);

        if ($validator->fails()) {
            return ['errors' => $validator->errors(), 'status' => 422];
        }

        $formRequest = FormsRequest::create($data);
        return ['formRequest' => $formRequest, 'status' => 201];
    }

    public function getFormRequestById($id)
    {
        return FormsRequest::find($id);
    }

    public function updateFormRequest($id, $data)
    {
        $validator = Validator::make($data, [
            'first_name' => 'sometimes|required|string',
            'last_name' => 'sometimes|required|string',
            'student_id' => 'sometimes|required|string',
            'document_type_id' => 'sometimes|required|exists:document_types,id',
        ]);

        if ($validator->fails()) {
            return ['errors' => $validator->errors(), 'status' => 422];
        }

        $formRequest = FormsRequest::find($id);
        if (!$formRequest) {
            return ['error' => 'Form Request not found', 'status' => 404];
        }

        $formRequest->update($data);
        return ['formRequest' => $formRequest, 'status' => 200];
    }

    public function deleteFormRequest($id)
    {
        $formRequest = FormsRequest::find($id);
        if (!$formRequest) {
            return ['error' => 'Form Request not found', 'status' => 404];
        }

        $formRequest->delete();
        return ['status' => 204];
    }
}
