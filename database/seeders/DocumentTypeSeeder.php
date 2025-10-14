<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $documentTypes = [
            [
                'type' => 'TRANSCRIPT OF RECORDS',
                'description' => 'Official academic transcript showing all courses and grades',
                'price' => 250.00,
                'processing_time' => '3-5 days',
                'is_active' => true
            ],
            [
                'type' => 'TRANSCRIPT OF RECORDS FOR EVALUATION',
                'description' => 'Transcript specifically for evaluation purposes',
                'price' => 300.00,
                'processing_time' => '5-7 days',
                'is_active' => true
            ],
            [
                'type' => 'FORM 137A',
                'description' => 'Secondary school record for incoming students',
                'price' => 200.00,
                'processing_time' => '2-3 days',
                'is_active' => true
            ],
            [
                'type' => 'FORM 138',
                'description' => 'Secondary school record for outgoing students',
                'price' => 200.00,
                'processing_time' => '2-3 days',
                'is_active' => true
            ],
            [
                'type' => 'HONORABLE DISMISSAL',
                'description' => 'Certificate for students transferring to another school',
                'price' => 150.00,
                'processing_time' => '1-2 days',
                'is_active' => true
            ],
            [
                'type' => 'DIPLOMA',
                'description' => 'Official graduation certificate',
                'price' => 350.00,
                'processing_time' => '5-7 days',
                'is_active' => true
            ],
            [
                'type' => 'CERTIFICATE OF NO OBJECTION',
                'description' => 'Certificate stating no objection to student activities',
                'price' => 100.00,
                'processing_time' => '1 day',
                'is_active' => true
            ],
            [
                'type' => 'CERTIFICATE OF ENGLISH AS MEDIUM',
                'description' => 'Certificate confirming English as medium of instruction',
                'price' => 100.00,
                'processing_time' => '1 day',
                'is_active' => true
            ],
            [
                'type' => 'CERTIFICATE OF GOOD MORAL',
                'description' => 'Certificate of good moral character',
                'price' => 100.00,
                'processing_time' => '1 day',
                'is_active' => true
            ],
            [
                'type' => 'CERTIFICATE OF REGISTRATION',
                'description' => 'Current enrollment status certificate',
                'price' => 100.00,
                'processing_time' => '1 day',
                'is_active' => true
            ],
            [
                'type' => 'CERTIFICATE OF COMPLETION',
                'description' => 'Certificate of course completion',
                'price' => 150.00,
                'processing_time' => '2-3 days',
                'is_active' => true
            ],
            [
                'type' => 'CERTIFICATE OF GRADES',
                'description' => 'Official grade certificate',
                'price' => 150.00,
                'processing_time' => '2-3 days',
                'is_active' => true
            ],
            [
                'type' => 'STATEMENT OF ACCOUNT',
                'description' => 'Financial statement of student account',
                'price' => 100.00,
                'processing_time' => '1 day',
                'is_active' => true
            ],
            [
                'type' => 'SERVICE RECORD',
                'description' => 'Employment service record',
                'price' => 200.00,
                'processing_time' => '3-5 days',
                'is_active' => true
            ],
            [
                'type' => 'EMPLOYMENT',
                'description' => 'Employment certificate',
                'price' => 200.00,
                'processing_time' => '3-5 days',
                'is_active' => true
            ],
            [
                'type' => 'PERFORMANCE RATING',
                'description' => 'Employee performance evaluation',
                'price' => 150.00,
                'processing_time' => '2-3 days',
                'is_active' => true
            ],
            [
                'type' => 'GWA CERTIFICATE',
                'description' => 'General Weighted Average certificate',
                'price' => 100.00,
                'processing_time' => '1 day',
                'is_active' => true
            ],
            [
                'type' => 'CAV ENDORSEMENT',
                'description' => 'Certification, Authentication, and Verification endorsement',
                'price' => 200.00,
                'processing_time' => '3-5 days',
                'is_active' => true
            ]
        ];

        foreach ($documentTypes as $documentType) {
            \App\Models\DocumentType::create($documentType);
        }
    }
}
