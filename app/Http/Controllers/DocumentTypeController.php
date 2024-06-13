<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DocumentTypeService;

class DocumentTypeController extends Controller
{
    protected $documentTypeService;

    public function __construct(DocumentTypeService $documentTypeService)
    {
        $this->documentTypeService = $documentTypeService;
    }

    public function index()
    {
        // Fetch all document types using the service
        $documentTypes = $this->documentTypeService->getAllDocumentTypes();

        // Return a JSON response with the document types
        return response()->json($documentTypes);
    }

    public function show($id)
    {
        // Find the document type by its ID using the service
        $documentType = $this->documentTypeService->getDocumentTypeById($id);

        // If the document type is not found, return a 404 error response
        if (!$documentType) {
            return response()->json(['error' => 'Document type not found'], 404);
        }

        // Return a JSON response with the document type
        return response()->json($documentType);
    }
}
