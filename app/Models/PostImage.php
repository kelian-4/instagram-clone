<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostImage extends Model
{
    protected $fillable = ['post_id', 'path', 'order'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /** URL publique de l'image */
   public function url(): string
{
    // Si c'est une URL externe (seeder), la retourner directement
    if (str_starts_with($this->path, 'http')) {
        return $this->path;
    }
    return asset('storage/' . $this->path);
}
}
