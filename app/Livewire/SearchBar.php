<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Post;
use Livewire\Component;

class SearchBar extends Component
{
    public string $query   = '';
    public array  $users   = [];
    public array  $hashtags = [];
    public bool   $open    = false;

    // Recherche en temps réel (debounce 300ms dans la vue)
    public function updatedQuery(): void
    {
        if (strlen($this->query) < 2) {
            $this->users    = [];
            $this->hashtags = [];
            $this->open     = false;
            return;
        }

        $this->users = User::where('username', 'like', "%{$this->query}%")
            ->orWhere('name', 'like', "%{$this->query}%")
            ->withCount('followers')
            ->limit(6)
            ->get()
            ->map(fn($u) => [
                'id'              => $u->id,
                'username'        => $u->username,
                'name'            => $u->name,
                'avatar'          => $u->avatarUrl(),
                'followers_count' => $u->followers_count,
            ])
            ->toArray();

        // Hashtags trouvés dans les captions
        $hashtagResults = Post::where('caption', 'like', "%#{$this->query}%")
            ->selectRaw("caption")
            ->get()
            ->flatMap(function ($post) {
                preg_match_all('/#([a-zA-Z0-9_]+)/i', $post->caption, $matches);
                return $matches[1] ?? [];
            })
            ->filter(fn($tag) => str_contains(strtolower($tag), strtolower($this->query)))
            ->unique()
            ->take(4)
            ->values()
            ->toArray();

        $this->hashtags = $hashtagResults;
        $this->open     = true;
    }

    public function close(): void
    {
        $this->open  = false;
        $this->query = '';
    }

    public function render()
    {
        return view('livewire.search-bar');
    }
}
