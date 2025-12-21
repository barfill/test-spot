<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;

class Dashboard extends Model
{
    use HasFactory, AuthorizesRequests;

    protected $fillable = [
        'created_by',
        'name',
        'description',
        'image_path',
        'image_disk',
        'is_active',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image_path || !$this->image_disk) {
            return null;
        }

        return Storage::disk($this->image_disk)->url($this->image_path);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }
}
