<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Carbon\CarbonInterval;
use Carbon\Carbon;


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

    public static array $statuses = ['open', 'closed'];

    protected $appends = ['ends_in', 'duration_days', 'end_date_formatted'];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
    ];

    public function getEndsInAttribute(): ?string
    {
        return $this->end_time?->diffForHumans(now(), true);
    }

    public function getDurationDaysAttribute(): ?int
    {
        if (!$this->start_time || !$this->end_time) {
            return null;
        }

        return $this->start_time->diffInDays($this->end_time);
    }

    public function getEndDateFormattedAttribute(): ?string
    {
        $locale = app()->getLocale();
        $format = $locale === 'pl' ? 'j M' : 'jS M';

        return $this->end_time ? $this->end_time->locale($locale)->translatedFormat($format) : null;
    }

    public function dashboard()
    {
        return $this->belongsTo(Dashboard::class);
    }

}

