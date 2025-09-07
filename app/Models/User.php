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
        'name',
        'email',
        'password',
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
}
