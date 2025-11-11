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

    protected $appends = ['ends_in', 'duration_days', 'end_date_formatted', 'start_time_formatted', 'end_time_formatted'];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
    ];

    public function getStartTimeAttribute($value)
    {
        if (!$value) return null;

        return Carbon::createFromFormat('Y-m-d H:i:s', $value, 'UTC')
            ->setTimezone('Europe/Warsaw');
    }

    public function getEndTimeAttribute($value)
    {
        if (!$value) return null;

        return Carbon::createFromFormat('Y-m-d H:i:s', $value, 'UTC')
            ->setTimezone('Europe/Warsaw');
    }

    public function setStartTimeAttribute($value)
    {
        if ($value) {
            $this->attributes['start_time'] = Carbon::parse($value, 'Europe/Warsaw')
                ->utc()
                ->format('Y-m-d H:i:s');
        }
    }

    public function setEndTimeAttribute($value)
    {
        if ($value) {
            $this->attributes['end_time'] = Carbon::parse($value, 'Europe/Warsaw')
                ->utc()
                ->format('Y-m-d H:i:s');
        }
    }

    public function getEndsInAttribute(): ?string
    {
        if (!$this->end_time) {
            return null;
        }

        $now = now();
        $end = $this->end_time;
        $locale = app()->getLocale();

        if ($end->isPast()) {
            return $locale === 'pl' ? 'Zakończone' : 'Ended';
        }

        $diff = $now->diffAsCarbonInterval($end);

        $weeks = (int) floor($diff->days / 7);
        $days = (int) ($diff->days % 7);
        $hours = (int) $diff->h;
        $minutes = (int) $diff->i;
        $seconds = (int) $diff->s;

        $parts = [];
        if ($weeks > 0) $parts[] = $locale === 'pl' ? "{$weeks} tyg" : "{$weeks} weeks";
        if ($days > 0) $parts[] = $locale === 'pl' ? "{$days} dni" : "{$days} days";
        if ($hours > 0) $parts[] = $locale === 'pl' ? "{$hours} h" : "{$hours} hrs";
        if ($minutes > 0) $parts[] = $locale === 'pl' ? "{$minutes} min" : "{$minutes} mins";

        if ($weeks === 0 && $days === 0 && $hours === 0 && $minutes === 0) {
            if ($seconds > 0) {
                return $locale === 'pl' ? 'mniej niż minutę' : 'less than a minute';
            } else {
                return $locale === 'pl' ? 'Zakończone' : 'Ended';
            }
        }

        return " " . implode(' ', $parts);
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

    public function getStartTimeFormattedAttribute(): ?string
    {
        if (!$this->start_time) return null;

        return $this->start_time->toIso8601String();
    }

    public function getEndTimeFormattedAttribute(): ?string
    {
        if (!$this->end_time) return null;

        return $this->end_time->toIso8601String();
    }

    public function dashboard()
    {
        return $this->belongsTo(Dashboard::class);
    }

    public function assignmentUsers()
    {
        return $this->hasMany(AssignmentUser::class);
    }
}

