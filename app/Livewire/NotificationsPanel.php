<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NotificationsPanel extends Component
{
    public bool $open        = false;
    public int  $unreadCount = 0;

    // Polling toutes les 30s
    protected $listeners = ['refreshNotifications' => '$refresh'];

    public function mount(): void
    {
        $this->unreadCount = Auth::user()->unreadNotifications()->count();
    }

    public function toggle(): void
    {
        $this->open = !$this->open;

        if ($this->open) {
            Auth::user()->unreadNotifications->markAsRead();
            $this->unreadCount = 0;
        }
    }

    public function getNotificationsProperty()
    {
        return Auth::user()->notifications()->latest()->limit(20)->get();
    }

    public function render()
    {
        // Rafraîchir le compteur à chaque render (polling)
        $this->unreadCount = Auth::user()->unreadNotifications()->count();

        return view('livewire.notifications-panel');
    }
}
