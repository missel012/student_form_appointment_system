<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentType;

class DocumentTypeController extends Controller
{
    public function index()
    {
        // Fetch all document types
        $documentTypes = DocumentType::all();

        // Return a JSON response with the document types
        return response()->json($documentTypes);
    }

    public function show($id)
    {
        // Find the document type by its ID
        $documentType = DocumentType::find($id);

        // If the document type is not found, return a 404 error response
        if (!$documentType) {
            return response()->json(['error' => 'Document type not found'], 404);
        }

        // Return a JSON response with the document type
        return response()->json($documentType);
    }
}