<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_request_id');
            $table->enum('sender_type', ['requester', 'registrar']);
            $table->unsignedBigInteger('sender_id')->nullable();
            $table->text('message');
            $table->timestamps();

            $table->foreign('document_request_id')->references('id')->on('document_requests')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};


