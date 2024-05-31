<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $documentTypes = [
            ['name' => 'Diploma Replace', 'price' => 50.00],
            ['name' => 'Evaluation', 'price' => 50.00],
            ['name' => 'Honorable Dismissal', 'price' => 100.00],
            ['name' => 'Correction of Name', 'price' => 100.00],
            ['name' => 'Transcript of Records (per page)', 'price' => 125.00],
            ['name' => 'Permit to Study', 'price' => 100.00],
            ['name' => 'Form 137', 'price' => 100.00],
        ];

        foreach ($documentTypes as $documentType) {
            DB::table('document_types')->insert($documentType);
        }
    }
}
