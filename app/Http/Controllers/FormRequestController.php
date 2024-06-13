<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FormRequestService;

class FormRequestController extends Controller
{
    protected $formRequestService;

    public function __construct(FormRequestService $formRequestService)
    {
        $this->formRequestService = $formRequestService;
    }

    public function index()
    {
        $formRequests = $this->formRequestService->getAllFormRequests();
        return response()->json($formRequests);
    }

    public function store(Request $request)
    {
        $result = $this->formRequestService->createFormRequest($request->all());

        if (isset($result['errors'])) {
            return response()->json(['errors' => $result['errors']], $result['status']);
        }

        return response()->json($result['formRequest'], $result['status']);
    }

    public function show($id)
    {
        $formRequest = $this->formRequestService->getFormRequestById($id);

        if (!$formRequest) {
            return response()->json(['error' => 'Form Request not found'], 404);
        }

        return response()->json($formRequest);
    }

    public function update(Request $request, $id)
    {
        $result = $this->formRequestService->updateFormRequest($id, $request->all());

        if (isset($result['errors'])) {
            return response()->json(['errors' => $result['errors']], $result['status']);
        }

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], $result['status']);
        }

        return response()->json($result['formRequest'], $result['status']);
    }

    public function destroy($id)
    {
        $result = $this->formRequestService->deleteFormRequest($id);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], $result['status']);
        }

        return response()->json(null, $result['status']);
    }
}
