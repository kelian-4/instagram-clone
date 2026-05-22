<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostMedia extends Model
{
    protected $fillable = ['post_id', 'path', 'type', 'order', 'alt_text'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function url(): string
{
    // Si le path est déjà une URL complète (seed data), on le retourne tel quel
    if (str_starts_with($this->path, 'http')) {
        return $this->path;
    }
    return asset('storage/' . $this->path);
}
}
