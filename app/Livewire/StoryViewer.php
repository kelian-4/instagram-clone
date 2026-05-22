<?php

namespace App\Livewire;

use App\Models\Story;
use App\Models\StoryView;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class StoryViewer extends Component
{
    public bool  $open       = false;
    public int   $userId     = 0;
    public int   $storyIndex = 0;
    public array $userStories = [];   // stories du user courant
    public array $allUsers    = [];   // liste des users avec stories

    // Écoute l'event Alpine/JS "openStory"
    protected $listeners = ['openStory'];

    public function openStory(int $userId): void
    {
        $this->userId = $userId;
        $this->open   = true;

        $stories = Story::active()
            ->where('user_id', $userId)
            ->with('user')
            ->get();

        $this->userStories = $stories->map(fn($s) => [
            'id'         => $s->id,
            'url'        => $s->mediaUrl(),
            'type'       => $s->type,
            'username'   => $s->user->username,
            'avatar'     => $s->user->avatarUrl(),
            'created_at' => $s->created_at->diffForHumans(),
        ])->toArray();

        $this->storyIndex = 0;
        $this->markCurrentViewed();
    }

    public function next(): void
    {
        if ($this->storyIndex < count($this->userStories) - 1) {
            $this->storyIndex++;
            $this->markCurrentViewed();
        } else {
            $this->close();
        }
    }

    public function prev(): void
    {
        if ($this->storyIndex > 0) {
            $this->storyIndex--;
        }
    }

    public function close(): void
    {
        $this->open = false;
    }

    private function markCurrentViewed(): void
    {
        if (!empty($this->userStories[$this->storyIndex])) {
            StoryView::firstOrCreate([
                'story_id' => $this->userStories[$this->storyIndex]['id'],
                'user_id'  => Auth::id(),
            ]);
        }
    }

    public function render()
    {
        return view('livewire.story-viewer');
    }
}
