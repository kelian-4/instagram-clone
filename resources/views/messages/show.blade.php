<x-app-layout>
<div style="position:fixed;inset:0;margin-left:72px;background:#0a0a0f;
display:flex;overflow:hidden">

<!-- ═══ COLONNE GAUCHE ═══ -->
<div style="width:360px;flex-shrink:0;display:flex;flex-direction:column;
border-right:1px solid #1e1e2e;background:#0e0e18">

<!-- Header -->
<div style="padding:16px 20px 0;flex-shrink:0">
<div style="display:flex;align-items:center;justify-content:space-between;
margin-bottom:16px">
<div style="display:flex;align-items:center;gap:8px">
<span style="font-weight:700;font-size:20px">
{{ auth()->user()->username }}
</span>
<svg width="14" height="14" viewBox="0 0 24 24" fill="none"
stroke="#a8a8a8" stroke-width="2" stroke-linecap="round">
<polyline points="6 9 12 15 18 9"/>
</svg>
</div>
<a href="{{ route('explore') }}"
style="color:#fff;text-decoration:none">
<svg width="22" height="22" viewBox="0 0 24 24" fill="none"
stroke="white" stroke-width="1.8" stroke-linecap="round"
stroke-linejoin="round">
<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
<path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5Z"/>
</svg>
</a>
</div>

<!-- Tabs -->
<div style="display:flex;border-bottom:1px solid #1e1e2e;margin:0 -20px;padding:0 20px">
<a href="{{ route('messages.index', ['tab'=>'primary']) }}"
style="flex:1;text-align:center;padding:10px 0;font-size:14px;
font-weight:600;text-decoration:none;
color:{{ $tab==='primary' ? '#fff' : '#a8a8a8' }};
border-bottom:{{ $tab==='primary' ? '2px solid #fff' : '2px solid transparent' }};
margin-bottom:-1px">Primary</a>
<a href="{{ route('messages.index', ['tab'=>'general']) }}"
style="flex:1;text-align:center;padding:10px 0;font-size:14px;
font-weight:600;text-decoration:none;
color:{{ $tab==='general' ? '#fff' : '#a8a8a8' }};
border-bottom:{{ $tab==='general' ? '2px solid #fff' : '2px solid transparent' }};
margin-bottom:-1px">General</a>
<a href="{{ route('messages.index', ['tab'=>'requests']) }}"
style="flex:1;text-align:center;padding:10px 0;font-size:14px;
font-weight:600;text-decoration:none;position:relative;
color:{{ $tab==='requests' ? '#fff' : '#a8a8a8' }};
border-bottom:{{ $tab==='requests' ? '2px solid #fff' : '2px solid transparent' }};
margin-bottom:-1px">
Demandes
@if($requests->count() > 0)
<span style="position:absolute;top:8px;right:4px;background:#ff3040;
color:#fff;font-size:9px;font-weight:700;min-width:14px;height:14px;
border-radius:7px;display:inline-flex;align-items:center;
justify-content:center;padding:0 3px">
{{ $requests->count() }}
</span>
@endif
</a>
</div>

<!-- Recherche -->
<div style="position:relative;margin:12px 0 8px">
<svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%)"
width="15" height="15" viewBox="0 0 24 24" fill="none"
stroke="#a8a8a8" stroke-width="2">
<circle cx="10.5" cy="10.5" r="7.5"/>
<line x1="16.5" y1="16.5" x2="22" y2="22"/>
</svg>
<input type="text" placeholder="Rechercher"
style="width:100%;background:#1e1e2e;border:none;border-radius:10px;
padding:9px 12px 9px 36px;color:#fff;font-size:14px;
outline:none;font-family:inherit;box-sizing:border-box">
</div>
</div>

<!-- Liste conversations -->
<div style="flex:1;overflow-y:auto">
@foreach($conversations as $conv)
@php $other2 = $conv->otherUser(auth()->id()); @endphp
@if($other2)
<a href="{{ route('messages.show', $other2) }}"
style="display:flex;align-items:center;gap:12px;padding:12px 20px;
text-decoration:none;color:#fff;transition:background .1s;
border-bottom:1px solid #111118;
background:{{ $other2->id === $user->id ? '#161628' : 'transparent' }}"
onmouseover="this.style.background='#131320'"
onmouseout="this.style.background='{{ $other2->id === $user->id ? '#161628' : 'transparent' }}'">

<div style="position:relative;flex-shrink:0">
<img src="{{ $other2->avatarUrl() }}" alt="{{ $other2->username }}"
style="width:56px;height:56px;border-radius:50%;object-fit:cover">
</div>
<div style="flex:1;min-width:0">
<div style="font-weight:600;font-size:14px;margin-bottom:3px">
{{ $other2->username }}
</div>
@php $lm = $conv->lastMessage; @endphp
<div style="font-size:13px;color:#a8a8a8;
overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
@if($lm)
@if($lm->user_id === auth()->id())
<span>Vous : </span>
@endif
{{ $lm->body }}
@endif
</div>
</div>
@php $unread2 = $conv->unreadCount(auth()->id()); @endphp
@if($unread2 > 0)
<div style="width:10px;height:10px;border-radius:50%;
background:#0095f6;flex-shrink:0"></div>
@endif
</a>
@endif
@endforeach
</div>
</div>

<!-- ═══ COLONNE DROITE — Chat ═══ -->
<div style="flex:1;display:flex;flex-direction:column;background:#0a0a0f">

<!-- Header chat -->
<div style="padding:16px 20px;border-bottom:1px solid #1e1e2e;
display:flex;align-items:center;gap:14px;flex-shrink:0;
background:#0e0e18">
<a href="{{ route('profile.show', $user->username) }}"
style="flex-shrink:0">
<img src="{{ $user->avatarUrl() }}" alt="{{ $user->username }}"
style="width:44px;height:44px;border-radius:50%;object-fit:cover">
</a>
<div style="flex:1;min-width:0">
<a href="{{ route('profile.show', $user->username) }}"
style="font-weight:700;font-size:16px;color:#fff;
text-decoration:none;display:block">
{{ $user->username }}
</a>
<span style="font-size:12px;color:#a8a8a8">{{ $user->name }}</span>
</div>
<!-- Actions header -->
<div style="display:flex;gap:16px;align-items:center">
<a href="{{ route('profile.show', $user->username) }}"
style="color:#a8a8a8;text-decoration:none">
<svg width="22" height="22" viewBox="0 0 24 24" fill="none"
stroke="currentColor" stroke-width="1.8">
<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
<circle cx="12" cy="7" r="4"/>
</svg>
</a>
</div>
</div>

<!-- Messages Livewire -->
@livewire('direct-messages', ['conversation' => $conversation])
</div>
</div>
</x-app-layout>
