<?php

namespace App\Livewire;

use App\Models\Conversation;
use App\Models\Message;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class DirectMessages extends Component
{
    public Conversation $conversation;
    public string       $body    = '';
    public array        $messages = [];

    protected array $rules = [
        'body' => 'required|string|max:1000',
    ];

    public function mount(Conversation $conversation): void
    {
        $this->conversation = $conversation;
        $this->loadMessages();
    }

    public function loadMessages(): void
    {
        $this->messages = $this->conversation
        ->messages()
        ->with('user')
        ->oldest()  // ← OLDEST pas latest — les récents en BAS
        ->get()
        ->map(fn($m) => [
            'id'         => $m->id,
            'body'       => $m->body,
            'user_id'    => $m->user_id,
            'username'   => $m->user->username,
            'avatar'     => $m->user->avatarUrl(),
              'mine'       => $m->user_id === Auth::id(),
              'created_at' => $m->created_at->format('H:i'),
              'read_at'    => $m->read_at,
        ])
        ->toArray();
    }


    public function send(): void
    {
        $this->validate();

        Message::create([
            'conversation_id' => $this->conversation->id,
            'user_id'         => Auth::id(),
            'body'            => $this->body,
        ]);

        $this->body = '';
        $this->loadMessages();
    }

    public function render()
    {
        // Polling toutes les 3s pour les nouveaux messages
        return view('livewire.direct-messages');
    }
}
