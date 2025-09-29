<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Assignment extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'dashboard_id',
        'name',
        'description',
        'start_time',
        'end_time',
        'status',
    ];

    public static array $statuses = ['pending', 'in_progress', 'completed'];

    public function dashboard()
    {
        return $this->belongsTo(Dashboard::class);
    }

}

