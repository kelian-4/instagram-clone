<?php

namespace App\Livewire;

use App\Models\Bookmark;
use App\Models\Post;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class BookmarkButton extends Component
{
    public Post $post;
    public bool $saved = false;

    public function mount(Post $post): void
    {
        $this->post  = $post;
        $this->saved = Bookmark::where('user_id', Auth::id())
            ->where('post_id', $post->id)
            ->exists();
    }

    public function toggle(): void
    {
        if (!Auth::check()) return;

        $existing = Bookmark::where('user_id', Auth::id())
            ->where('post_id', $this->post->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $this->saved = false;
        } else {
            Bookmark::create(['user_id' => Auth::id(), 'post_id' => $this->post->id]);
            $this->saved = true;
        }
    }

    public function render()
    {
        return view('livewire.bookmark-button');
    }
}
