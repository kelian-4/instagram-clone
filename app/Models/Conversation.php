<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Conversation extends Model
{
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('folder', 'status')
            ->withTimestamps();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->latest();
    }

    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latest();
    }

    public function otherUser(int $currentUserId): ?User
    {
        return $this->users->firstWhere('id', '!=', $currentUserId);
    }

    /** Folder de l'utilisateur courant pour cette conversation */
    public function myFolder(): string
    {
        $pivot = $this->users
            ->firstWhere('id', Auth::id())
            ?->pivot;
        return $pivot?->folder ?? 'primary';
    }

    /** Status de l'utilisateur courant */
    public function myStatus(): string
    {
        $pivot = $this->users
            ->firstWhere('id', Auth::id())
            ?->pivot;
        return $pivot?->status ?? 'accepted';
    }

    public function unreadCount(int $userId): int
    {
        return $this->messages()
            ->where('user_id', '!=', $userId)
            ->whereNull('read_at')
            ->count();
    }
}
