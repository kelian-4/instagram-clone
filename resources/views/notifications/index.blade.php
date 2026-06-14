<x-app-layout>
<div style="max-width:600px;margin:0 auto;padding:20px">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px">
        <h2 style="font-size:22px;font-weight:700">Notifications</h2>
        <form action="{{ route('notifications.read-all') }}" method="POST">
            @csrf
            <button type="submit" style="color:#0095f6;font-size:14px;font-weight:600;
                                         background:none;border:none;cursor:pointer">
                Tout marquer comme lu
            </button>
        </form>
    </div>

    @forelse($notifications as $notif)
    @php $data = $notif->data; @endphp
    <div style="display:flex;align-items:center;gap:14px;padding:10px 12px;
                border-radius:8px;margin-bottom:4px;transition:background 0.1s;
                background:{{ $notif->read_at ? 'transparent' : '#0d1117' }}"
         onmouseover="this.style.background='#111'"
         onmouseout="this.style.background='{{ $notif->read_at ? 'transparent' : '#0d1117' }}'">

        <!-- Avatar -->
        <div style="position:relative;flex-shrink:0">
            <img src="{{ $data['liker_avatar'] ?? $data['follower_avatar'] ?? $data['commenter_avatar'] ?? '' }}"
                 alt="" style="width:44px;height:44px;border-radius:50%;object-fit:cover">
            <!-- Type badge -->
            <div style="position:absolute;bottom:-2px;right:-2px;width:20px;height:20px;
                        border-radius:50%;border:2px solid #000;display:flex;
                        align-items:center;justify-content:center;
                        background:{{ $data['type'] === 'like' ? '#ff3040' : ($data['type'] === 'follow' ? '#0095f6' : '#a855f7') }}">
                @if($data['type'] === 'like')
                <svg width="10" height="10" viewBox="0 0 24 24" fill="white">
                    <path d="M16.792 3.904A4.989 4.989 0 0 1 21.5 9.122c0 3.517-4.903 7.574-9.5 10.378-4.597-2.804-9.5-6.861-9.5-10.378a4.989 4.989 0 0 1 4.708-5.218 4.21 4.21 0 0 1 3.675 1.941c.84 1.175.98 1.763 1.12 1.763s.278-.588 1.11-1.766a4.17 4.17 0 0 1 3.679-1.938m0-2a6.04 6.04 0 0 0-4.797 2.127 6.052 6.052 0 0 0-4.787-2.127A6.985 6.985 0 0 0 .5 9.122c0 3.61 2.55 7.097 7.16 10.124a50.153 50.153 0 0 0 4.34 2.555 50.154 50.154 0 0 0 4.342-2.555C20.95 16.22 23.5 12.733 23.5 9.122a6.985 6.985 0 0 0-6.708-7.218Z"/>
                </svg>
                @elseif($data['type'] === 'follow')
                <svg width="10" height="10" viewBox="0 0 24 24" fill="white">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                </svg>
                @else
                <svg width="10" height="10" viewBox="0 0 24 24" fill="white">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
                @endif
            </div>
        </div>

        <!-- Texte -->
        <div style="flex:1;font-size:14px;line-height:1.5">
            @if($data['type'] === 'like')
            <a href="{{ route('profile.show', $data['liker_name']) }}"
               style="font-weight:700;color:#fff">{{ $data['liker_name'] }}</a>
            a aimé votre publication.
            @elseif($data['type'] === 'follow')
            <a href="{{ route('profile.show', $data['follower_name']) }}"
               style="font-weight:700;color:#fff">{{ $data['follower_name'] }}</a>
            a commencé à vous suivre.
            @elseif($data['type'] === 'comment')
            <a href="{{ route('profile.show', $data['commenter_name']) }}"
               style="font-weight:700;color:#fff">{{ $data['commenter_name'] }}</a>
            a commenté :
            <span style="color:#a8a8a8">{{ $data['comment_body'] }}</span>
            @endif
            <span style="color:#a8a8a8;font-size:11px;margin-left:4px">
                {{ $notif->created_at->diffForHumans() }}
            </span>
        </div>

        <!-- Miniature post -->
        @if(!empty($data['post_thumb']))
        <a href="{{ isset($data['post_id']) ? route('posts.show', $data['post_id']) : '#' }}"
           style="flex-shrink:0">
            <img src="{{ $data['post_thumb'] }}" alt=""
                 style="width:44px;height:44px;object-fit:cover;border-radius:4px">
        </a>
        @elseif($data['type'] === 'follow')
        @php $followUser = \App\Models\User::where('username', $data['follower_name'])->first(); @endphp
        @if($followUser)
        @livewire('follow-button', ['target' => $followUser], key('notif-follow-'.$notif->id))
        @endif
        @endif
    </div>
    @empty
    <div style="text-align:center;padding:60px;color:#a8a8a8">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none"
             stroke="#a8a8a8" stroke-width="1" style="margin:0 auto 16px;display:block">
            <path d="M16.792 3.904A4.989 4.989 0 0 1 21.5 9.122c0 3.517-4.903 7.574-9.5 10.378-4.597-2.804-9.5-6.861-9.5-10.378a4.989 4.989 0 0 1 4.708-5.218 4.21 4.21 0 0 1 3.675 1.941c.84 1.175.98 1.763 1.12 1.763s.278-.588 1.11-1.766a4.17 4.17 0 0 1 3.679-1.938m0-2a6.04 6.04 0 0 0-4.797 2.127 6.052 6.052 0 0 0-4.787-2.127A6.985 6.985 0 0 0 .5 9.122c0 3.61 2.55 7.097 7.16 10.124a50.153 50.153 0 0 0 4.34 2.555 50.154 50.154 0 0 0 4.342-2.555C20.95 16.22 23.5 12.733 23.5 9.122a6.985 6.985 0 0 0-6.708-7.218Z"/>
        </svg>
        <h3 style="color:#fff;font-size:18px;font-weight:700;margin-bottom:8px">
            Aucune notification
        </h3>
        <p style="font-size:14px">Quand quelqu'un aime ou commente votre post, vous le verrez ici.</p>
    </div>
    @endforelse

    @if($notifications->hasPages())
    <div style="margin-top:20px">{{ $notifications->links() }}</div>
    @endif
</div>
</x-app-layout>
