<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_requests', function (Blueprint $table) {
            // ...
        
            $table->id();
            $table->string('student_id', 50);
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);
            $table->string('course', 100);
            $table->string('province', 100);
            $table->string('city', 100);
            $table->string('barangay', 100);
            $table->string('mobile_number', 20);
            $table->string('email', 100);
            $table->string('purpose', 100);
            $table->text('special_instructions')->nullable();
            $table->enum('status', ['pending','rejected','processing','completed','approved'])->default('pending');
            $table->enum('payment_status', ['paid','unpaid'])->default('unpaid');
            $table->timestamp('paid_at')->nullable();
            $table->dateTime('request_date');
            $table->timestamp('created_at')->useCurrent();
            $table->string('reference_number', 100)->nullable();
            $table->string('year_level', 20)->nullable();
            $table->json('school_years')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_requests');
    }
}
