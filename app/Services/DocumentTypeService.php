<?php

namespace App\Services;

use App\Models\DocumentType;

class DocumentTypeService
{
    public function getAllDocumentTypes()
    {
        return DocumentType::all();
    }

    public function getDocumentTypeById($id)
    {
        return DocumentType::find($id);
    }
}
