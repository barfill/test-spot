<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('assignment_users', function (Blueprint $table) {
            $table->json('ai_generated_report')->nullable()->after('test_cases_results');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignment_users', function (Blueprint $table) {
            $table->dropColumn('ai_generated_report');
        });
    }
};
