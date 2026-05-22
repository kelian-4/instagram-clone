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
<!-- Nouvelle conversation -->
<a href="{{ route('explore') }}"
style="display:flex;align-items:center;justify-content:center;
color:#fff;text-decoration:none">
<svg width="22" height="22" viewBox="0 0 24 24" fill="none"
stroke="white" stroke-width="1.8" stroke-linecap="round"
stroke-linejoin="round">
<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
<path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5Z"/>
</svg>
</a>
</div>

<!-- Tabs Primary / General / Requests -->
<div style="display:flex;border-bottom:1px solid #1e1e2e;margin:0 -20px;
padding:0 20px">
<a href="{{ route('messages.index', ['tab' => 'primary']) }}"
style="flex:1;text-align:center;padding:10px 0;font-size:14px;
font-weight:600;text-decoration:none;
color:{{ $tab === 'primary' ? '#fff' : '#a8a8a8' }};
border-bottom:{{ $tab === 'primary' ? '2px solid #fff' : '2px solid transparent' }};
margin-bottom:-1px;transition:color .15s">
Primary
</a>
<a href="{{ route('messages.index', ['tab' => 'general']) }}"
style="flex:1;text-align:center;padding:10px 0;font-size:14px;
font-weight:600;text-decoration:none;
color:{{ $tab === 'general' ? '#fff' : '#a8a8a8' }};
border-bottom:{{ $tab === 'general' ? '2px solid #fff' : '2px solid transparent' }};
margin-bottom:-1px;transition:color .15s">
General
</a>
<a href="{{ route('messages.index', ['tab' => 'requests']) }}"
style="flex:1;text-align:center;padding:10px 0;font-size:14px;
font-weight:600;text-decoration:none;position:relative;
color:{{ $tab === 'requests' ? '#fff' : '#a8a8a8' }};
border-bottom:{{ $tab === 'requests' ? '2px solid #fff' : '2px solid transparent' }};
margin-bottom:-1px;transition:color .15s">
Demandes
@if($requests->count() > 0)
<span style="position:absolute;top:8px;right:8px;
background:#ff3040;color:#fff;font-size:9px;
font-weight:700;min-width:14px;height:14px;
border-radius:7px;display:inline-flex;
align-items:center;justify-content:center;padding:0 3px">
{{ $requests->count() }}
</span>
@endif
</a>
</div>

<!-- Barre de recherche -->
<div style="position:relative;margin:12px 0 8px">
<svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%)"
width="15" height="15" viewBox="0 0 24 24" fill="none"
stroke="#a8a8a8" stroke-width="2">
<circle cx="10.5" cy="10.5" r="7.5"/>
<line x1="16.5" y1="16.5" x2="22" y2="22"/>
</svg>
<input type="text" placeholder="Rechercher"
id="msg-search"
style="width:100%;background:#1e1e2e;border:none;
border-radius:10px;padding:9px 12px 9px 36px;
color:#fff;font-size:14px;outline:none;
font-family:inherit;box-sizing:border-box">
</div>
</div>

<!-- ── Liste conversations ── -->
<div style="flex:1;overflow-y:auto" id="conv-list">

@if($tab === 'requests')
{{-- ── DEMANDES ── --}}
@forelse($conversations as $conv)
@php
$other   = $conv->otherUser(auth()->id());
$lastMsg = $conv->lastMessage;
@endphp
@if($other)
<div style="padding:12px 20px;border-bottom:1px solid #1a1a2e"
x-data="{ accepting: false, folder: 'primary' }">

<div style="display:flex;gap:12px;align-items:flex-start">
<img src="{{ $other->avatarUrl() }}" alt=""
style="width:56px;height:56px;border-radius:50%;
object-fit:cover;flex-shrink:0">
<div style="flex:1;min-width:0">
<div style="font-weight:700;font-size:14px;margin-bottom:3px">
{{ $other->username }}
</div>
<div style="color:#a8a8a8;font-size:13px;margin-bottom:10px;
overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
@if($lastMsg)
{{ $lastMsg->body }}
· {{ $lastMsg->created_at->diffForHumans(null, true) }}
@endif
</div>

<!-- Actions demande -->
<div x-show="!accepting" style="display:flex;gap:8px;flex-wrap:wrap">
<!-- Accepter -->
<button @click="accepting = true"
style="padding:7px 16px;background:#0095f6;border:none;
border-radius:8px;color:#fff;font-size:13px;
font-weight:700;cursor:pointer">
Accepter
</button>
<!-- Refuser -->
<form action="{{ route('messages.decline', $conv) }}"
method="POST" style="display:inline">
@csrf
<button type="submit"
style="padding:7px 16px;background:#1e1e2e;border:none;
border-radius:8px;color:#fff;font-size:13px;
font-weight:600;cursor:pointer">
Refuser
</button>
</form>
<!-- Bloquer -->
<form action="{{ route('messages.block', $conv) }}"
method="POST" style="display:inline">
@csrf
<button type="submit"
style="padding:7px 16px;background:#1e1e2e;border:none;
border-radius:8px;color:#ff3040;font-size:13px;
font-weight:600;cursor:pointer">
Bloquer
</button>
</form>
</div>

<!-- Choix dossier après acceptation -->
<div x-show="accepting" style="display:flex;flex-direction:column;gap:8px">
<p style="color:#a8a8a8;font-size:13px;margin:0">
Ajouter dans :
</p>
<div style="display:flex;gap:8px">
<form action="{{ route('messages.accept', $conv) }}"
method="POST" style="display:inline">
@csrf
<input type="hidden" name="folder" value="primary">
<button type="submit"
style="padding:7px 16px;background:#0095f6;
border:none;border-radius:8px;color:#fff;
font-size:13px;font-weight:700;cursor:pointer">
Primary
</button>
</form>
<form action="{{ route('messages.accept', $conv) }}"
method="POST" style="display:inline">
@csrf
<input type="hidden" name="folder" value="general">
<button type="submit"
style="padding:7px 16px;background:#1e1e2e;
border:none;border-radius:8px;color:#fff;
font-size:13px;font-weight:600;cursor:pointer">
General
</button>
</form>
<button @click="accepting = false"
style="padding:7px 12px;background:transparent;
border:none;color:#a8a8a8;font-size:13px;
cursor:pointer">
Annuler
</button>
</div>
</div>
</div>
</div>
</div>
@endif
@empty
<div style="text-align:center;padding:40px 20px;color:#a8a8a8;font-size:14px">
Aucune demande de message.
</div>
@endforelse

@else
{{-- ── PRIMARY / GENERAL ── --}}
@forelse($conversations as $conv)
@php
$other    = $conv->otherUser(auth()->id());
$lastMsg  = $conv->lastMessage;
$unread   = $conv->unreadCount(auth()->id());
@endphp
@if($other)
<a href="{{ route('messages.show', $other) }}"
style="display:flex;align-items:center;gap:12px;padding:12px 20px;
text-decoration:none;color:#fff;transition:background .1s;
border-bottom:1px solid #111118"
onmouseover="this.style.background='#131320'"
onmouseout="this.style.background='transparent'"
id="msg-item-{{ $conv->id }}">

<!-- Avatar -->
<div style="position:relative;flex-shrink:0">
<img src="{{ $other->avatarUrl() }}" alt="{{ $other->username }}"
style="width:56px;height:56px;border-radius:50%;object-fit:cover">
@if($other->hasActiveStory())
<div style="position:absolute;inset:-2px;border-radius:50%;
border:2px solid transparent;
background:linear-gradient(#0e0e18,#0e0e18) padding-box,
var(--ig-gradient) border-box">
</div>
@endif
</div>

<!-- Infos -->
<div style="flex:1;min-width:0">
<div style="display:flex;align-items:center;justify-content:space-between;
margin-bottom:3px">
<span style="font-weight:{{ $unread > 0 ? '700' : '600' }};
font-size:14px;color:#fff">
{{ $other->username }}
</span>
@if($lastMsg)
<span style="color:#a8a8a8;font-size:12px;white-space:nowrap;margin-left:8px">
{{ $lastMsg->created_at->diffForHumans(null, true) }}
</span>
@endif
</div>
<div style="font-size:13px;
color:{{ $unread > 0 ? '#fff' : '#a8a8a8' }};
font-weight:{{ $unread > 0 ? '600' : '400' }};
overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
@if($lastMsg)
@if($lastMsg->user_id === auth()->id())
<span style="color:#a8a8a8">Vous : </span>
@endif
{{ $lastMsg->body }}
@else
<span style="color:#a8a8a8;font-style:italic">Démarrer une conversation</span>
@endif
</div>
</div>

<!-- Badge non-lu + options -->
<div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px;flex-shrink:0">
@if($unread > 0)
<div style="width:10px;height:10px;border-radius:50%;
background:#0095f6;flex-shrink:0">
</div>
@endif

<!-- Swipe menu (3 points) -->
<div x-data="{ open: false }" style="position:relative"
@click.stop>
<button @click="open = !open"
style="background:none;border:none;cursor:pointer;
padding:2px;opacity:0.6;color:#fff"
onmouseover="this.style.opacity='1'"
onmouseout="this.style.opacity='0.6'">
<svg width="16" height="16" viewBox="0 0 24 24" fill="white">
<circle cx="12" cy="5"  r="1.2"/>
<circle cx="12" cy="12" r="1.2"/>
<circle cx="12" cy="19" r="1.2"/>
</svg>
</button>
<div x-show="open" x-cloak @click.outside="open = false"
style="position:absolute;right:0;top:20px;background:#1e1e2e;
border-radius:12px;padding:6px 0;min-width:160px;
box-shadow:0 4px 20px rgba(0,0,0,0.6);z-index:50">
<form action="{{ route('messages.move', $conv) }}" method="POST">
@csrf
<input type="hidden" name="folder"
value="{{ $conv->myFolder() === 'primary' ? 'general' : 'primary' }}">
<button type="submit"
style="width:100%;padding:10px 14px;text-align:left;
font-size:13px;color:#fff;background:none;
border:none;cursor:pointer">
Déplacer vers
{{ $conv->myFolder() === 'primary' ? 'General' : 'Primary' }}
</button>
</form>
<form action="{{ route('messages.block', $conv) }}" method="POST">
@csrf
<button type="submit"
style="width:100%;padding:10px 14px;text-align:left;
font-size:13px;color:#ff3040;background:none;
border:none;cursor:pointer">
Bloquer
</button>
</form>
</div>
</div>
</div>
</a>
@endif
@empty
<div style="text-align:center;padding:40px 20px;color:#a8a8a8;font-size:14px">
@if($tab === 'general')
Aucun message dans General.
@else
Aucun message dans Primary.
@endif
</div>
@endforelse
@endif
</div>
</div>

<!-- ═══ COLONNE DROITE — vide ═══ -->
<div style="flex:1;display:flex;flex-direction:column;
align-items:center;justify-content:center;gap:16px;
background:#0a0a0f">

<!-- Icône avion centré dans cercle -->
<div style="width:96px;height:96px;border-radius:50%;
border:2px solid #fff;display:flex;
align-items:center;justify-content:center">
<svg width="44" height="44" viewBox="0 0 24 24" fill="none"
stroke="white" stroke-width="1.5" stroke-linecap="round"
stroke-linejoin="round">
<path d="M22 2L11 13"/>
<path d="M22 2L15 22L11 13L2 9L22 2Z"/>
</svg>
</div>

<h3 style="font-size:20px;font-weight:700;color:#fff;margin:0">
Vos messages
</h3>
<p style="color:#a8a8a8;font-size:14px;text-align:center;
max-width:280px;margin:0;line-height:1.5">
Envoyez des photos et des messages privés à un(e) ami(e) ou à un groupe.
</p>
<a href="{{ route('explore') }}"
style="background:#0095f6;color:#fff;padding:9px 20px;
border-radius:8px;font-size:14px;font-weight:700;
text-decoration:none;transition:opacity .2s"
onmouseover="this.style.opacity='.85'"
onmouseout="this.style.opacity='1'">
Envoyer un message
</a>
</div>
</div>

<script>
// Filtre recherche en temps réel
document.getElementById('msg-search')?.addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('[id^="msg-item-"]').forEach(el => {
        const name = el.querySelector('span')?.textContent?.toLowerCase() || '';
    el.style.display = name.includes(q) || q === '' ? 'flex' : 'none';
    });
});
</script>
</x-app-layout>
