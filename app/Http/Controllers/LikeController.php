<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /** Toggle like sur un post (appelé aussi via Livewire) */
    public function toggle(Post $post)
    {
        $user = Auth::user();

        $existing = Like::where('user_id', $user->id)
            ->where('likeable_id', $post->id)
            ->where('likeable_type', Post::class)
            ->first();

        if ($existing) {
            $existing->delete();
            $post->decrement('likes_count');
            $liked = false;
        } else {
            Like::create([
                'user_id'       => $user->id,
                'likeable_id'   => $post->id,
                'likeable_type' => Post::class,
            ]);
            $post->increment('likes_count');
            $liked = true;

            // Notification au propriétaire du post (si ce n'est pas soi-même)
            if ($post->user_id !== $user->id) {
                $post->user->notify(new \App\Notifications\PostLiked($user, $post));
            }
        }

        return response()->json([
            'liked'       => $liked,
            'likes_count' => $post->fresh()->likes_count,
        ]);
    }
}
