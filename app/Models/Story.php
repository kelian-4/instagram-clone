<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Story extends Model
{
    protected $fillable = ['user_id', 'media_path', 'type', 'expires_at'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function views(): HasMany
    {
        return $this->hasMany(StoryView::class);
    }

    /** Scope : seulement les stories non-expirées */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('expires_at', '>', now());
    }

    public function mediaUrl(): string
{
    if (str_starts_with($this->media_path, 'http')) {
        return $this->media_path;
    }
    return asset('storage/' . $this->media_path);
}
}
