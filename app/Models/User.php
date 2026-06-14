<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'username', 'email', 'password', 'avatar',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ─── Relations ───────────────────────────────────────────────

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function stories(): HasMany
    {
        return $this->hasMany(Story::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    // Utilisateurs que JE suis
    public function following(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class, 'follows', 'follower_id', 'following_id'
        )->withTimestamps();
    }

    // Utilisateurs qui ME suivent
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class, 'follows', 'following_id', 'follower_id'
        )->withTimestamps();
    }

    public function conversations(): BelongsToMany
    {
        return $this->belongsToMany(Conversation::class)->withTimestamps();
    }

    // ─── Helpers ─────────────────────────────────────────────────

    public function isFollowing(User $user): bool
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    public function avatarUrl(): string
    {
        if ($this->avatar && str_starts_with($this->avatar, 'http')) {
            return $this->avatar;
        }
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : "https://ui-avatars.com/api/?name=" . urlencode($this->username) . "&background=333&color=fff";
    }

    /** Story active = au moins une story non-expirée */
    public function hasActiveStory(): bool
    {
        return $this->stories()->where('expires_at', '>', now())->exists();
    }
}
