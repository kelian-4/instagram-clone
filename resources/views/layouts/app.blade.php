<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $title ?? 'Instagram' }}</title>
<link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
@vite(['resources/css/app.css','resources/js/app.js'])
@livewireStyles
</head>
<body class="{{ request()->routeIs('messages.*') ? 'hide-float-msg' : '' }}">

<!-- ═══════════════════════════════════════
SIDEBAR DESKTOP
═══════════════════════════════════════ -->
<nav class="ig-sidebar">

<a href="{{ route('feed') }}" class="ig-nav-item">
<div class="ig-nav-icon">
<svg width="26" height="26" viewBox="0 0 24 24" fill="none"
stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
<rect x="2" y="2" width="20" height="20" rx="6"/>
<circle cx="12" cy="12" r="4"/>
<circle cx="17.2" cy="6.8" r="1" fill="white" stroke="none"/>
</svg>
</div>
<span class="ig-nav-label ig-logo-text">Instagram</span>
</a>

<a href="{{ route('feed') }}" class="ig-nav-item {{ request()->routeIs('feed') ? 'active' : '' }}">
<div class="ig-nav-icon">
<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
<path d="M3 12L12 3l9 9"/><path d="M9 21V12h6v9"/><path d="M3 21h18"/>
</svg>
</div>
<span class="ig-nav-label">Accueil</span>
</a>

<a href="{{ route('reels.index') }}" class="ig-nav-item {{ request()->routeIs('reels.*') ? 'active' : '' }}">
<div class="ig-nav-icon">
<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
<rect x="2" y="2" width="20" height="20" rx="6"/>
<polygon points="10 8 16 12 10 16" fill="white" stroke="none"/>
</svg>
</div>
<span class="ig-nav-label">Reels</span>
</a>

<a href="{{ route('messages.index') }}" class="ig-nav-item {{ request()->routeIs('messages.*') ? 'active' : '' }}">
<div class="ig-nav-icon" style="position:relative">
<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
stroke="white" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
<path d="M22 2L11 13"/><path d="M22 2L15 22L11 13L2 9L22 2Z"/>
</svg>
@php
try { $unreadMsg = auth()->user()->conversations()
    ->with(['messages'=>fn($q)=>$q->where('user_id','!=',auth()->id())->whereNull('read_at')])
    ->get()->sum(fn($c)=>$c->messages->count());
} catch(\Exception $e){ $unreadMsg=0; }
@endphp
@if($unreadMsg > 0)
<span class="ig-badge">{{ $unreadMsg }}</span>
@endif
</div>
<span class="ig-nav-label">Messages</span>
</a>

<button class="ig-nav-item"
onclick="document.getElementById('search-panel').classList.toggle('open')">
<div class="ig-nav-icon">
<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
stroke="white" stroke-width="1.8" stroke-linecap="round">
<circle cx="10.5" cy="10.5" r="7.5"/>
<line x1="16.5" y1="16.5" x2="22" y2="22"/>
</svg>
</div>
<span class="ig-nav-label">Recherche</span>
</button>

<a href="{{ route('explore') }}" class="ig-nav-item {{ request()->routeIs('explore') ? 'active' : '' }}">
<div class="ig-nav-icon">
<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
<circle cx="12" cy="12" r="10"/>
<polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/>
</svg>
</div>
<span class="ig-nav-label">Découvrir</span>
</a>

<button class="ig-nav-item" x-data @click="$dispatch('open-notifications')">
<div class="ig-nav-icon" style="position:relative">
<svg width="24" height="24" viewBox="0 0 32 32" fill="none"
stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
<path d="M16 28S4 20 4 12a8 8 0 0 1 12-6.928A8 8 0 0 1 28 12c0 8-12 16-12 16Z"/>
</svg>
@if(auth()->user()->unreadNotifications()->count() > 0)
<span class="ig-badge">{{ auth()->user()->unreadNotifications()->count() }}</span>
@endif
</div>
<span class="ig-nav-label">Notifications</span>
</button>

<button class="ig-nav-item" onclick="Livewire.dispatch('openCreatePost')">
<div class="ig-nav-icon">
<svg width="26" height="26" viewBox="0 0 24 24" fill="none"
stroke="white" stroke-width="1.8" stroke-linecap="round">
<line x1="12" y1="5" x2="12" y2="19"/>
<line x1="5"  y1="12" x2="19" y2="12"/>
</svg>
</div>
<span class="ig-nav-label">Créer</span>
</button>

<a href="#" class="ig-nav-item">
<div class="ig-nav-icon">
<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
<rect x="3"  y="3"  width="7" height="7" rx="2"/>
<rect x="14" y="3"  width="7" height="7" rx="2"/>
<rect x="3"  y="14" width="7" height="7" rx="2"/>
<rect x="14" y="14" width="7" height="7" rx="2"/>
</svg>
</div>
<span class="ig-nav-label">Tableau de bord</span>
</a>

<a href="{{ route('profile.show', auth()->user()->username) }}"
class="ig-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
<div class="ig-nav-icon">
<div class="ig-avatar-ring {{ auth()->user()->hasActiveStory() ? 'has-story' : '' }}">
<img src="{{ auth()->user()->avatarUrl() }}" alt="" class="ig-avatar-img">
</div>
</div>
<span class="ig-nav-label">Profil</span>
</a>

<div class="ig-nav-item" x-data="{ open: false }" style="position:relative">
<div class="ig-nav-icon" @click="open = !open">
<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
stroke="white" stroke-width="1.8" stroke-linecap="round">
<line x1="3" y1="7"  x2="21" y2="7"/>
<line x1="3" y1="12" x2="21" y2="12"/>
<line x1="3" y1="17" x2="21" y2="17"/>
</svg>
</div>
<span class="ig-nav-label" @click="open = !open">Plus</span>
<div x-show="open" x-cloak @click.outside="open=false" class="ig-more-popup">
<a href="{{ route('profile.edit') }}" class="ig-more-item">Paramètres</a>
<a href="#" class="ig-more-item">Votre activité</a>
<a href="{{ route('bookmarks.index') }}" class="ig-more-item">Enregistrements</a>
<div class="ig-more-divider"></div>
<a href="#" class="ig-more-item">Changer l'apparence</a>
<a href="#" class="ig-more-item">Signaler un problème</a>
<div class="ig-more-divider"></div>
<form method="POST" action="{{ route('logout') }}">
@csrf
<button type="submit" class="ig-more-item" style="width:100%;text-align:left">
Déconnexion
</button>
</form>
</div>
</div>

<a href="#" class="ig-nav-item">
<div class="ig-nav-icon">
<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8">
<circle cx="7"  cy="7"  r="2.5"/>
<circle cx="17" cy="7"  r="2.5"/>
<circle cx="7"  cy="17" r="2.5"/>
<circle cx="17" cy="17" r="2.5"/>
</svg>
</div>
<span class="ig-nav-label">Autres applications</span>
</a>
</nav>

<!-- Search panel -->
<div id="search-panel"
style="position:fixed;top:0;left:72px;width:0;height:100vh;
background:var(--ig-sidebar-bg);overflow:hidden;
transition:width .25s cubic-bezier(0.4,0,0.2,1);
z-index:99;border-right:1px solid var(--ig-border)">
<div style="padding:16px;width:300px">
<h2 style="font-size:22px;font-weight:700;margin-bottom:16px">Recherche</h2>
@livewire('search-bar')
</div>
</div>
<div id="search-overlay"
style="position:fixed;inset:0;z-index:98;display:none"
onclick="document.getElementById('search-panel').classList.remove('open')"></div>

@livewire('notifications-panel')
@livewire('create-post')
@livewire('story-viewer')

<!-- ═══════════════════════════════════════
MOBILE HEADER
(logo centré + icône créer gauche + icône notifications droite)
═══════════════════════════════════════ -->
<header class="ig-mobile-header">
<!-- Bouton créer (haut gauche mobile) -->
<button onclick="Livewire.dispatch('openCreatePost')"
style="background:none;border:none;cursor:pointer;padding:4px;color:#fff">
<svg width="26" height="26" viewBox="0 0 24 24" fill="none"
stroke="white" stroke-width="1.8" stroke-linecap="round">
<line x1="12" y1="5" x2="12" y2="19"/>
<line x1="5"  y1="12" x2="19" y2="12"/>
</svg>
</button>

<!-- Logo centré -->
<span class="ig-mobile-logo">Instagram</span>

<!-- Notifications + search droite -->
<div style="display:flex;gap:14px;align-items:center">
<button onclick="document.getElementById('search-panel').classList.toggle('open')"
style="background:none;border:none;cursor:pointer;padding:2px">
<svg width="22" height="22" viewBox="0 0 24 24" fill="none"
stroke="white" stroke-width="1.8" stroke-linecap="round">
<circle cx="10.5" cy="10.5" r="7.5"/>
<line x1="16.5" y1="16.5" x2="22" y2="22"/>
</svg>
</button>
<a href="{{ route('notifications') }}" style="position:relative;color:#fff">
<svg width="22" height="22" viewBox="0 0 32 32" fill="none"
stroke="white" stroke-width="1.8" stroke-linecap="round">
<path d="M16 28S4 20 4 12a8 8 0 0 1 12-6.928A8 8 0 0 1 28 12c0 8-12 16-12 16Z"/>
</svg>
@if(auth()->user()->unreadNotifications()->count() > 0)
<span style="position:absolute;top:-3px;right:-3px;width:8px;height:8px;
background:var(--ig-red);border-radius:50%;
border:1.5px solid var(--ig-bg)"></span>
@endif
</a>
</div>
</header>

<!-- Contenu principal -->
<main class="ig-main">{{ $slot }}</main>

<!-- ═══════════════════════════════════════
MOBILE NAV BAS — ordre 2026
Home → Reels → DMs → Search → Profile
═══════════════════════════════════════ -->
<nav class="ig-mobile-nav">

<!-- 1. Home -->
<a href="{{ route('feed') }}"
class="ig-mobile-nav-item {{ request()->routeIs('feed') ? 'active' : '' }}">
@if(request()->routeIs('feed'))
<svg width="26" height="26" viewBox="0 0 24 24"
fill="white" stroke="none">
<path d="M3 12L12 3l9 9v9h-6v-6H9v6H3v-9z"/>
</svg>
@else
<svg width="26" height="26" viewBox="0 0 24 24" fill="none"
stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
<path d="M3 12L12 3l9 9"/><path d="M9 21V12h6v9"/><path d="M3 21h18"/>
</svg>
@endif
</a>

<!-- 2. Reels -->
<a href="{{ route('reels.index') }}"
class="ig-mobile-nav-item {{ request()->routeIs('reels.*') ? 'active' : '' }}">
<svg width="26" height="26" viewBox="0 0 24 24" fill="none"
stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
<rect x="2" y="2" width="20" height="20" rx="6"/>
<polygon points="10 8 16 12 10 16"
fill="{{ request()->routeIs('reels.*') ? 'white' : 'white' }}"
stroke="none"/>
</svg>
</a>

<!-- 3. DMs (Messages) -->
<a href="{{ route('messages.index') }}"
class="ig-mobile-nav-item {{ request()->routeIs('messages.*') ? 'active' : '' }}"
style="position:relative">
<svg width="26" height="26" viewBox="0 0 24 24" fill="none"
stroke="white"
stroke-width="{{ request()->routeIs('messages.*') ? '2.2' : '1.8' }}"
stroke-linecap="round" stroke-linejoin="round">
<path d="M22 2L11 13"/>
<path d="M22 2L15 22L11 13L2 9L22 2Z"/>
</svg>
@if(isset($unreadMsg) && $unreadMsg > 0)
<span style="position:absolute;top:6px;right:8px;width:8px;height:8px;
background:var(--ig-red);border-radius:50%;
border:1.5px solid var(--ig-bg)"></span>
@endif
</a>

<!-- 4. Search -->
<a href="{{ route('explore') }}"
class="ig-mobile-nav-item {{ request()->routeIs('explore') ? 'active' : '' }}">
<svg width="26" height="26" viewBox="0 0 24 24" fill="none"
stroke="white"
stroke-width="{{ request()->routeIs('explore') ? '2.2' : '1.8' }}"
stroke-linecap="round">
<circle cx="10.5" cy="10.5" r="7.5"/>
<line x1="16.5" y1="16.5" x2="22" y2="22"/>
</svg>
</a>

<!-- 5. Profile -->
<a href="{{ route('profile.show', auth()->user()->username) }}"
class="ig-mobile-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
<div style="width:26px;height:26px;border-radius:50%;overflow:hidden;
border:{{ request()->routeIs('profile.*') ? '2px solid #fff' : '1px solid #555' }}">
<img src="{{ auth()->user()->avatarUrl() }}" alt=""
style="width:100%;height:100%;object-fit:cover;display:block">
</div>
</a>
</nav>

<!-- Bouton flottant Messages (caché mobile + page messages) -->
<a href="{{ route('messages.index') }}" class="ig-float-msg">
@php
try {
    $floatUnread = auth()->user()->conversations()
    ->with(['messages'=>fn($q)=>$q->where('user_id','!=',auth()->id())->whereNull('read_at')])
    ->get()->sum(fn($c)=>$c->messages->count());
} catch(\Exception $e){ $floatUnread=0; }
@endphp
<div style="position:relative">
<svg width="20" height="20" viewBox="0 0 24 24" fill="none"
stroke="#000" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
<path d="M22 2L11 13"/><path d="M22 2L15 22L11 13L2 9L22 2Z"/>
</svg>
@if($floatUnread > 0)
<span style="position:absolute;top:-5px;right:-5px;background:var(--ig-red);
color:#fff;font-size:9px;font-weight:700;min-width:14px;height:14px;
border-radius:7px;display:flex;align-items:center;justify-content:center;
padding:0 2px;border:2px solid #fff">{{ $floatUnread }}</span>
@endif
</div>
Messages
</a>

@livewireScripts
<script>
document.addEventListener('DOMContentLoaded', function() {
    const panel   = document.getElementById('search-panel');
    const overlay = document.getElementById('search-overlay');
    if (!panel) return;
    const obs = new MutationObserver(() => {
        const open = panel.classList.contains('open');
        panel.style.width  = open ? '300px' : '0';
    overlay.style.display = open ? 'block' : 'none';
    });
    obs.observe(panel, { attributes: true, attributeFilter: ['class'] });
});
</script>
</body>
</html>
