<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Temporarily change status column to VARCHAR to avoid enum constraints
        DB::statement("ALTER TABLE document_requests MODIFY COLUMN status VARCHAR(50) DEFAULT 'pending'");
        
        // Update existing data to use new status values
        DB::statement("UPDATE document_requests SET status = 'pending_registrar_approval' WHERE status = 'pending'");
        
        // Now change back to ENUM with new values
        DB::statement("ALTER TABLE document_requests MODIFY COLUMN status ENUM('pending_verification', 'pending_registrar_approval', 'approved', 'rejected', 'processing', 'completed') DEFAULT 'pending_verification'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Temporarily change status column to VARCHAR
        DB::statement("ALTER TABLE document_requests MODIFY COLUMN status VARCHAR(50) DEFAULT 'pending'");
        
        // Update existing data back to old status values
        DB::statement("UPDATE document_requests SET status = 'pending' WHERE status = 'pending_registrar_approval'");
        
        // Revert status enum to original values
        DB::statement("ALTER TABLE document_requests MODIFY COLUMN status ENUM('pending','rejected','processing','completed','approved') DEFAULT 'pending'");
    }
};
