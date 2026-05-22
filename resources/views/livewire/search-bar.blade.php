<div style="position:relative">
    <div style="position:relative;margin-bottom:8px">
        <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%)"
             width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#a8a8a8" stroke-width="2">
            <path d="M19 10.5A8.5 8.5 0 1 1 10.5 2a8.5 8.5 0 0 1 8.5 8.5Z"/>
            <line x1="16.511" y1="16.511" x2="22" y2="22"/>
        </svg>
        <input type="text"
               wire:model.live.debounce.300ms="query"
               placeholder="Rechercher"
               style="width:100%;background:#262626;border:none;border-radius:8px;
                      padding:10px 10px 10px 38px;color:#fff;font-size:14px;outline:none">
    </div>

    @if($open)
    <div style="margin-top:4px">
        @forelse($users as $user)
        <a href="{{ route('profile.show', $user['username']) }}"
           wire:click="close"
           style="display:flex;align-items:center;gap:12px;padding:10px 8px;
                  border-radius:8px;transition:background 0.1s;text-decoration:none;color:#fff"
           onmouseover="this.style.background='#1a1a1a'" onmouseout="this.style.background='transparent'">
            <img src="{{ $user['avatar'] }}" alt=""
                 style="width:44px;height:44px;border-radius:50%;object-fit:cover">
            <div>
                <div style="font-weight:600;font-size:14px">{{ $user['username'] }}</div>
                <div style="color:#a8a8a8;font-size:12px">{{ $user['name'] }} · {{ $user['followers_count'] }} abonnés</div>
            </div>
        </a>
        @empty
            @if(empty($hashtags))
            <div style="padding:16px;text-align:center;color:#a8a8a8;font-size:14px">Aucun résultat.</div>
            @endif
        @endforelse

        @foreach($hashtags as $tag)
        <a href="{{ route('explore', ['tag' => $tag]) }}"
           wire:click="close"
           style="display:flex;align-items:center;gap:12px;padding:10px 8px;
                  border-radius:8px;transition:background 0.1s;text-decoration:none;color:#fff"
           onmouseover="this.style.background='#1a1a1a'" onmouseout="this.style.background='transparent'">
            <div style="width:44px;height:44px;border-radius:50%;background:#262626;
                        display:flex;align-items:center;justify-content:center;font-size:20px">#</div>
            <div>
                <div style="font-weight:600;font-size:14px">#{{ $tag }}</div>
                <div style="color:#a8a8a8;font-size:12px">Hashtag</div>
            </div>
        </a>
        @endforeach
    </div>
    @endif
</div>
