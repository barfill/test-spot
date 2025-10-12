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
            $table->dropPrimary(['assignment_id', 'user_id']);
            $table->id()->first();
            $table->unique(['assignment_id', 'user_id'], 'assignment_user_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignment_users', function (Blueprint $table) {
            $table->dropUnique('assignment_user_unique');
            $table->dropColumn('id');
            $table->primary(['assignment_id', 'user_id']);
        });
    }
};
