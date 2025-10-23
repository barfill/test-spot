<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\AssignmentUser;
use App\Models\User;
use App\Models\Assignment;
use PhpParser\Node\Expr\Assign;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assignment_users', function (Blueprint $table) {
            $table->foreignIdFor(Assignment::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->string('file_path')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->enum('status', AssignmentUser::$status);
            $table->integer('grade')->nullable();
            $table->text('review_comment')->nullable();
            $table->enum('plagiarism_check_result', AssignmentUser::$checkResults)->nullable();
            $table->enum('compilation_check_result', AssignmentUser::$checkResults)->nullable();
            $table->enum('edge_cases_check_result', AssignmentUser::$checkResults)->nullable();

            $table->primary(['assignment_id', 'user_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_users');
    }
};
