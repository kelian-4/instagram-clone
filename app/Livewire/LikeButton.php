<?php

namespace App\Livewire;

use App\Models\Like;
use App\Models\Post;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class LikeButton extends Component
{
    public int  $postId     = 0;
    public bool $liked      = false;
    public int  $likesCount = 0;

    public function mount(Post $post): void
    {
        $this->postId     = $post->id;
        $this->likesCount = (int) $post->fresh()->likes_count;
        $this->liked      = Auth::check()
        && Like::where('user_id', Auth::id())
        ->where('likeable_id', $post->id)
        ->where('likeable_type', Post::class)
        ->exists();
    }

    // Changement ici : On retire ": void" pour autoriser les "return" de coupure de script
    public function toggle()
    {
        if (!Auth::check()) return;

        $post = Post::find($this->postId);
        if (!$post) return;

        $existing = Like::where('user_id', Auth::id())
        ->where('likeable_id', $this->postId)
        ->where('likeable_type', Post::class)
        ->first();

        if ($existing) {
            $existing->delete();
            $post->decrement('likes_count');
            $this->liked      = false;
            $this->likesCount = max(0, $this->likesCount - 1);
        } else {
            Like::create([
                'user_id'       => Auth::id(),
                         'likeable_id'   => $this->postId,
                         'likeable_type' => Post::class,
            ]);
            $post->increment('likes_count');
            $this->liked      = true;
            $this->likesCount = $this->likesCount + 1;

            if ($post->user_id !== Auth::id()) {
                $post->user->notify(
                    new \App\Notifications\PostLiked(Auth::user(), $post)
                );
            }
        }
    }

    public function render()
    {
        return view('livewire.like-button');
    }
}
