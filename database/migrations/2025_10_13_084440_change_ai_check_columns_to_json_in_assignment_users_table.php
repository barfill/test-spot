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
            $table->dropColumn('plagiarism_check_result');
            $table->dropColumn('compilation_check_result');
            $table->dropColumn('edge_cases_check_result');
        });

        Schema::table('assignment_users', function (Blueprint $table) {
            $table->json('plagiarism_check_result')->nullable()->after('review_comment');
            $table->json('compilation_check_result')->nullable()->after('plagiarism_check_result');
            $table->json('edge_cases_check_result')->nullable()->after('compilation_check_result');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignment_users', function (Blueprint $table) {
            $table->dropColumn('plagiarism_check_result');
            $table->dropColumn('compilation_check_result');
            $table->dropColumn('edge_cases_check_result');
        });

        Schema::table('assignment_users', function (Blueprint $table) {
            $table->enum('plagiarism_check_result', ['passed', 'failed', 'suspended'])->nullable();
            $table->enum('compilation_check_result', ['passed', 'failed', 'suspended'])->nullable();
            $table->enum('edge_cases_check_result', ['passed', 'failed', 'suspended'])->nullable();
        });
    }
};
