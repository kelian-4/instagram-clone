<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class FollowButton extends Component
{
    public User $target;
    public bool $following      = false;
    public int  $followersCount = 0;

    public function mount(User $target): void
    {
        $this->target         = $target;
        $this->followersCount = $target->followers()->count();
        $this->following      = Auth::check() && Auth::user()->isFollowing($target);
    }

    public function toggle(): void
    {
        if (!Auth::check() || Auth::id() === $this->target->id) return;

        $me = Auth::user();

        if ($me->isFollowing($this->target)) {
            $me->following()->detach($this->target->id);
            $this->following = false;
            $this->followersCount--;
        } else {
            $me->following()->attach($this->target->id);
            $this->following = true;
            $this->followersCount++;

            $this->target->notify(new \App\Notifications\NewFollower($me));
        }
    }

    public function render()
    {
        return view('livewire.follow-button');
    }
}
