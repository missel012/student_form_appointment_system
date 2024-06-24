<?php

namespace App\Http\Controllers; // Declare the namespace for the Controllers

use Illuminate\Http\Request; // Import the Request class from Illuminate\Http
use App\Services\DocumentTypeService; // Import the DocumentTypeService class from App\Services

class DocumentTypeController extends Controller // Define the DocumentTypeController class extending the base Controller class
{
    protected $documentTypeService; // Declare a protected property for the DocumentTypeService

    public function __construct(DocumentTypeService $documentTypeService) // Constructor method to initialize the DocumentTypeService
    {
        $this->documentTypeService = $documentTypeService; // Assign the injected DocumentTypeService instance to the property
    }

    public function index() // Method to handle fetching all document types
    {
        // Fetch all document types using the service
        $documentTypes = $this->documentTypeService->getAllDocumentTypes();

        // Return a JSON response with the document types
        return response()->json($documentTypes);
    }

    public function show($id) // Method to handle fetching a specific document type by ID
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
