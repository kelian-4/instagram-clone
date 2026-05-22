<div wire:poll.10000ms>
    <div x-data="{ open: false }"
         @open-notifications.window="open = !open; if(open) $wire.set('open', true)"
         x-show="open"
         x-cloak
         @click.outside="open = false; $wire.set('open', false)"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-x-2"
         x-transition:enter-end="opacity-100 translate-x-0"
         style="position:fixed;top:0;left:72px;width:320px;height:100vh;
                background:#000;border-right:1px solid #262626;z-index:99;
                overflow-y:auto;padding:16px">

        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
            <h2 style="font-size:22px;font-weight:700">Notifications</h2>
            <button @click="open = false" style="color:#a8a8a8;background:none;border:none;cursor:pointer">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        @forelse($this->notifications as $notif)
        @php $data = $notif->data; @endphp
        <div style="display:flex;align-items:center;gap:12px;padding:8px;border-radius:8px;
                    margin-bottom:4px;background:{{ $notif->read_at ? 'transparent' : '#111' }}">
            <img src="{{ $data['liker_avatar'] ?? $data['follower_avatar'] ?? $data['commenter_avatar'] ?? '' }}"
                 alt="" style="width:44px;height:44px;border-radius:50%;object-fit:cover;flex-shrink:0">
            <div style="flex:1;font-size:14px;line-height:1.4">
                @if(($data['type'] ?? '') === 'like')
                    <span style="font-weight:600">{{ $data['liker_name'] }}</span> a aimé votre publication.
                @elseif(($data['type'] ?? '') === 'follow')
                    <span style="font-weight:600">{{ $data['follower_name'] }}</span> a commencé à vous suivre.
                @elseif(($data['type'] ?? '') === 'comment')
                    <span style="font-weight:600">{{ $data['commenter_name'] }}</span> a commenté :
                    <span style="color:#a8a8a8">{{ $data['comment_body'] ?? '' }}</span>
                @endif
                <div style="color:#a8a8a8;font-size:11px;margin-top:2px">
                    {{ $notif->created_at->diffForHumans() }}
                </div>
            </div>
            @if(!empty($data['post_thumb']))
            <img src="{{ $data['post_thumb'] }}" alt=""
                 style="width:44px;height:44px;object-fit:cover;border-radius:4px;flex-shrink:0">
            @endif
        </div>
        @empty
        <div style="text-align:center;color:#a8a8a8;padding:32px 0;font-size:14px">
            Aucune notification pour l'instant.
        </div>
        @endforelse
    </div>
</div>
