<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentUser extends Model
{
    /** @use HasFactory<\Database\Factories\AssignmentUserFactory> */
    use HasFactory;

    protected $table = 'assignment_users';

    protected $fillable = [
        'assignment_id',
        'user_id',
        'file_path',
        'user_comment',
        'submitted_at',
        'status',
        'grade',
        'review_comment',
        'plagiarism_check_result',
        'compilation_check_result',
        'edge_cases_check_result',
    ];

    public static array $status = ['in_progress', 'pending', 'graded_passed', 'graded_failed'];
    public static array $checkResults = ['passed', 'failed', 'suspended'];

    protected $casts = [
        'submitted_at' => 'datetime',
        'grade' => 'integer',
        'plagiarism_check_result' => 'array',
        'compilation_check_result' => 'array',
        'edge_cases_check_result' => 'array',
    ];

    public function assignment() {
        return $this->belongsTo(Assignment::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
