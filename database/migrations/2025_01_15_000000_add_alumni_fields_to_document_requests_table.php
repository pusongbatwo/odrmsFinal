<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAlumniFieldsToDocumentRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('document_requests', 'alumni_id')) {
                $table->string('alumni_id', 50)->nullable()->after('student_id');
            }
            if (!Schema::hasColumn('document_requests', 'graduation_year')) {
                $table->string('graduation_year', 20)->nullable()->after('alumni_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('document_requests', function (Blueprint $table) {
            $table->dropColumn(['alumni_id', 'graduation_year']);
        });
    }
}
