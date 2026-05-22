<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Post extends Model
{
    protected $fillable = [
        'user_id', 'caption', 'location', 'is_reel',
        'likes_count', 'comments_count','comments_enabled',
    ];

    protected $casts = [
        'is_reel' => 'boolean',
    ];

    // ─── Relations ───────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(PostMedia::class)->orderBy('order');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id')->latest();
    }

    public function allComments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    // ─── Helpers ─────────────────────────────────────────────────

    public function isLikedBy(User $user): bool
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function isBookmarkedBy(User $user): bool
    {
        return $this->bookmarks()->where('user_id', $user->id)->exists();
    }

    /** Premier média du post */
    public function thumbnail(): ?PostMedia
    {
        return $this->media->first();
    }

    /** Parse le caption pour rendre #hashtags et @mentions cliquables */
    public function parsedCaption(): string
    {
        $caption = e($this->caption ?? '');

        // @mentions
        $caption = preg_replace(
            '/@([a-zA-Z0-9_.]+)/',
            '<a href="' . route('profile.show', ['username' => '$1']) . '" class="text-blue-400 hover:underline">@$1</a>',
            $caption
        );

        // #hashtags
        $caption = preg_replace(
            '/#([a-zA-Z0-9_]+)/',
            '<a href="/explore?tag=$1" class="text-blue-400 hover:underline">#$1</a>',
            $caption
        );

        return $caption;
    }
}
