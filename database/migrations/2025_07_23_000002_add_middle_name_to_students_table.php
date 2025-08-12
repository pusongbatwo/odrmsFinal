<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        if (!Schema::hasColumn('students', 'middle_name')) {
            Schema::table('students', function (Blueprint $table) {
                $table->string('middle_name')->nullable()->after('first_name');
            });
        }
    }
    public function down() {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('middle_name');
        });
    }
};
