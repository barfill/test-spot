<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** @method \Illuminate\Database\Eloquent\Relations\BelongsToMany userDashboards() */
/** @property-read \Illuminate\Database\Eloquent\Collection<int,\App\Models\Dashboard> $userDashboards */

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public static array $type = ['student', 'teacher', 'admin'];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'password',
        'type',
        'title',
        'first_name',
        'last_name',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function dashboards(): HasMany
    {
        return $this->hasMany(Dashboard::class, 'created_by');
    }

    public function userDashboards(): BelongsToMany
    {
        return $this->belongsToMany(Dashboard::class)->withTimestamps();
    }

    public function assignmentUsers()
    {
        return $this->hasMany(AssignmentUser::class);
    }

    public function scopeDashboardUsersAndNoAssignedStudentsWithMembershipFlag($query, $dashboardId) {
        return $query
            ->select(
                'users.*'
            )
            ->selectRaw('
                CASE
		        WHEN dashboard_user.dashboard_id IS NOT NULL AND dashboard_user.user_id IS NOT NULL
                    THEN 1
                    ELSE 0
                END AS is_in_dashboard')
            ->leftJoin('dashboard_user', function ($join) use ($dashboardId){
                $join->on('users.id', '=', 'dashboard_user.user_id')
                    ->where('dashboard_user.dashboard_id', '=', $dashboardId);
            })
            ->where(function ($q) {
                $q->where('users.type', 'student')
                  ->orWhereNotNull('dashboard_user.dashboard_id');
            })
            ->orderByDesc('is_in_dashboard')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->orderBy('email')
            ->distinct()
        ;
    }

    public function scopeAllUsers($query) {
        return $query
            ->select([
                'users.*',
            ])
            ->orderBy('last_name')
            ->orderBy('first_name');
    }
}
