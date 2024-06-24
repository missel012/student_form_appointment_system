<?php

namespace App\Services;

use App\Models\DocumentType; // Import the DocumentType model

class DocumentTypeService
{
    /**
     * Retrieve all document types from the database.
     *
     * @return \Illuminate\Database\Eloquent\Collection|\App\Models\DocumentType[]  Collection of all document types
     */
    public function getAllDocumentTypes()
    {
        return DocumentType::all(); // Retrieve all document types using Eloquent ORM
    }

    /**
     * Retrieve a document type by its ID from the database.
     *
     * @param  int  $id  Document type ID
     * @return \App\Models\DocumentType|null  DocumentType instance if found, otherwise null
     */
    public function getDocumentTypeById($id)
    {
        return DocumentType::find($id); // Retrieve a document type by ID using Eloquent ORM
    }
}

