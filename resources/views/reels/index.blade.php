<x-app-layout>

<div style="position:fixed;inset:0;background:#000;z-index:50;
margin-left:72px;overflow:hidden;display:flex;
align-items:center;justify-content:center"
x-data="reelsPlayer({{ json_encode($reels->map(fn($r) => [
'id'           => $r->id,
'video'        => $r->media->first()?->url(),
'username'     => $r->user->username,
'avatar'       => $r->user->avatarUrl(),
'caption'      => $r->caption ?? '',
'likes'        => (int)$r->likes_count,
'comments'     => (int)$r->comments_count,
'reposts'      => rand(100, 5000),
'shares'       => rand(10, 500),
'liked'        => $r->likes->contains('user_id', auth()->id()),
'reposted'     => false,
'bookmarked'   => false,
'followed'     => false,
'post_url'     => route('posts.show', $r->id),
'profile_url'  => route('profile.show', $r->user->username),
'user_id'      => $r->user->id,
])->values()) }})">

<div style="position:relative;height:100vh;display:flex;
align-items:center;justify-content:center;flex:1">

<div style="position:relative;height:calc(100vh - 0px);
max-height:100vh;aspect-ratio:9/16;
background:#111;border-radius:0;overflow:hidden;
max-width:420px;width:100%">

<div style="width:100%;height:100%;overflow-y:auto;
scroll-snap-type:y mandatory"
id="reels-container"
@scroll.passive="onScroll($event)">

<template x-for="(reel, index) in reels" :key="reel.id">
<div style="height:100%;width:100%;scroll-snap-align:start;
position:relative;flex-shrink:0"
:id="'reel-' + index">

<video :id="'video-' + index"
:src="reel.video"
loop playsinline preload="metadata"
style="width:100%;height:100%;
object-fit:cover;display:block;cursor:pointer"
@click="togglePlay(index)"
@dblclick.prevent="likeReel(index)">
</video>

<div style="position:absolute;inset:0;pointer-events:none;
background:linear-gradient(
    to top,
rgba(0,0,0,0.85) 0%,
rgba(0,0,0,0.1) 45%,
transparent 70%)">
</div>

<div x-show="showPlayIcon && currentIndex === index"
x-transition:leave="transition duration-300"
x-transition:leave-end="opacity-0"
style="position:absolute;top:50%;left:50%;
transform:translate(-50%,-50%);
pointer-events:none;z-index:10">
<div style="width:56px;height:56px;border-radius:50%;
background:rgba(0,0,0,0.55);display:flex;
align-items:center;justify-content:center">
<svg x-show="!isPlaying" width="24" height="24"
viewBox="0 0 24 24" fill="white">
<polygon points="5 3 19 12 5 21 5 3"/>
</svg>
<svg x-show="isPlaying" width="24" height="24"
viewBox="0 0 24 24" fill="white">
<rect x="6" y="4" width="4" height="16"/>
<rect x="14" y="4" width="4" height="16"/>
</svg>
</div>
</div>

<div x-show="heartAnim && currentIndex === index"
x-transition:enter="transition duration-100"
x-transition:enter-start="opacity-0 scale-50"
x-transition:enter-end="opacity-100 scale-100"
x-transition:leave="transition duration-500"
x-transition:leave-end="opacity-0 scale-150"
style="position:absolute;top:50%;left:50%;
transform:translate(-50%,-50%);
pointer-events:none;z-index:20">
<svg width="90" height="90" viewBox="0 0 32 32"
fill="#ff3040"
style="filter:drop-shadow(0 2px 12px rgba(255,48,64,0.7))">
<path d="M16 28S4 20 4 12a8 8 0 0 1 12-6.928A8 8 0 0 1 28 12c0 8-12 16-12 16Z"/>
</svg>
</div>

<button @click="toggleMute()"
style="position:absolute;bottom:100px;right:12px;
background:rgba(0,0,0,0.45);border:none;
border-radius:50%;width:32px;height:32px;
cursor:pointer;display:flex;align-items:center;
justify-content:center;z-index:10">
<svg x-show="muted" width="15" height="15"
viewBox="0 0 24 24" fill="none"
stroke="white" stroke-width="2" stroke-linecap="round">
<polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/>
<line x1="23" y1="9" x2="17" y2="15"/>
<line x1="17" y1="9" x2="23" y2="15"/>
</svg>
<svg x-show="!muted" width="15" height="15"
viewBox="0 0 24 24" fill="none"
stroke="white" stroke-width="2" stroke-linecap="round">
<polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/>
<path d="M15.54 8.46a5 5 0 0 1 0 7.07"/>
</svg>
</button>

<div style="position:absolute;bottom:20px;left:12px;
right:10px;z-index:10">
<div style="display:flex;align-items:center;
gap:8px;margin-bottom:8px">
<a :href="reel.profile_url">
<img :src="reel.avatar" alt=""
style="width:34px;height:34px;border-radius:50%;
object-fit:cover;border:2px solid #fff">
</a>
<a :href="reel.profile_url"
style="text-decoration:none">
<span style="font-weight:700;font-size:14px;color:#fff;
text-shadow:0 1px 3px rgba(0,0,0,0.6)"
x-text="reel.username"></span>
</a>
<button @click="followUser(index)"
:style="reel.followed
? 'background:rgba(255,255,255,0.15);border:1.5px solid rgba(255,255,255,0.4);color:#fff'
: 'background:transparent;border:1.5px solid #fff;color:#fff'"
style="padding:4px 14px;border-radius:8px;
font-size:13px;font-weight:700;cursor:pointer;
transition:all .2s;margin-left:2px">
<span x-text="reel.followed ? 'Abonné' : 'Suivre'"></span>
</button>
</div>

<div style="font-size:13px;line-height:1.5;color:#fff;
text-shadow:0 1px 3px rgba(0,0,0,0.6);
max-width:calc(100% - 10px)"
x-text="reel.caption">
</div>

<div style="display:flex;align-items:center;gap:5px;margin-top:8px">
<svg width="12" height="12" viewBox="0 0 24 24" fill="white">
<path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/>
<circle cx="18" cy="16" r="3"/>
</svg>
<span style="font-size:12px;color:#fff;
text-shadow:0 1px 3px rgba(0,0,0,0.6)"
x-text="'Son original · ' + reel.username">
</span>
</div>
</div>

<div style="position:absolute;bottom:0;left:0;right:0;
height:2px;background:rgba(255,255,255,0.15);z-index:10">
<div :style="'width:' + (progress[index]||0) + '%;height:100%;background:#fff;transition:width .1s linear'">
</div>
</div>
</div>
</template>
</div>
</div>
</div>

<div style="width:80px;display:flex;flex-direction:column;
align-items:center;justify-content:flex-end;
padding-bottom:60px;gap:20px;flex-shrink:0;
padding-right:4px">

<div style="display:flex;flex-direction:column;align-items:center;gap:4px">
<button @click="likeReel(currentIndex)"
style="background:none;border:none;cursor:pointer;
padding:6px;display:flex;align-items:center;
justify-content:center">
<svg width="30" height="30" viewBox="0 0 32 32"
:fill="reels[currentIndex]?.liked ? '#ff3040' : 'none'"
:stroke="reels[currentIndex]?.liked ? '#ff3040' : 'white'"
stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
style="transition:all .2s;filter:drop-shadow(0 1px 4px rgba(0,0,0,0.4))">
<path d="M16 28S4 20 4 12a8 8 0 0 1 12-6.928A8 8 0 0 1 28 12c0 8-12 16-12 16Z"/>
</svg>
</button>
<span style="color:#fff;font-size:13px;font-weight:600"
x-text="formatCount(reels[currentIndex]?.likes || 0)"></span>
</div>

<div style="display:flex;flex-direction:column;align-items:center;gap:4px">
<a :href="reels[currentIndex]?.post_url"
style="padding:6px;display:flex;align-items:center;
justify-content:center;text-decoration:none">
<svg width="28" height="28" viewBox="0 0 24 24"
fill="none" stroke="white" stroke-width="1.8"
stroke-linecap="round" stroke-linejoin="round"
style="filter:drop-shadow(0 1px 4px rgba(0,0,0,0.4))">
<path d="M20.656 17.008a9.993 9.993 0 1 0-3.59 3.615L22 22Z"/>
</svg>
</a>
<span style="color:#fff;font-size:13px;font-weight:600"
x-text="formatCount(reels[currentIndex]?.comments || 0)"></span>
</div>

<div style="display:flex;flex-direction:column;align-items:center;gap:4px">
<button @click="repostReel(currentIndex)"
style="background:none;border:none;cursor:pointer;
padding:6px;display:flex;align-items:center;
justify-content:center">
<svg width="28" height="28" viewBox="0 0 24 24" fill="none"
:stroke="reels[currentIndex]?.reposted ? '#00ba7c' : 'white'"
stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
style="transition:stroke .2s;
filter:drop-shadow(0 1px 4px rgba(0,0,0,0.4))">
<polyline points="17 2 21 6 17 10"/>
<path d="M3 11V9a4 4 0 0 1 4-4h14"/>
<polyline points="7 22 3 18 7 14"/>
<path d="M21 13v2a4 4 0 0 1-4 4H3"/>
</svg>
</button>
<span style="color:#fff;font-size:13px;font-weight:600"
:style="reels[currentIndex]?.reposted ? 'color:#00ba7c' : ''"
x-text="formatCount(reels[currentIndex]?.reposts || 0)"></span>
</div>

<div style="display:flex;flex-direction:column;align-items:center;gap:4px;
position:relative"
x-data="{
shareOpen: false,
selectedUsers: [],
sending: false,
sent: false,
toggle(id) {
const i = this.selectedUsers.indexOf(id);
if (i === -1) this.selectedUsers.push(id);
else this.selectedUsers.splice(i, 1);
},
isSelected(id) { return this.selectedUsers.includes(id); },
copyLink() {
if (reels[currentIndex]) {
    navigator.clipboard.writeText(reels[currentIndex].post_url);
    $dispatch('toast', '🔗 Lien copié !');
    this.shareOpen = false;
    }
    },
async sendDM() {
if (!this.selectedUsers.length || this.sending) return;
this.sending = true;
const reel = reels[currentIndex];
try {
for (const uid of this.selectedUsers) {
    await fetch('/share-post', {
    method: 'POST',
headers: {
'Content-Type': 'application/json',
'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
},
body: JSON.stringify({
user_id: uid,
post_url: reel.post_url,
post_id: reel.id
})
});
}
this.sent = true;
$dispatch('toast', '✓ Envoyé !');
setTimeout(() => {
this.shareOpen = false;
this.sent = false;
this.selectedUsers = [];
}, 1200);
} catch(e) {
$dispatch('toast', 'Erreur réseau');
} finally {
this.sending = false;
}
}
}">

<button @click="shareOpen = !shareOpen"
style="background:none;border:none;cursor:pointer;
padding:6px;display:flex;align-items:center;
justify-content:center">
<svg width="26" height="26" viewBox="0 0 24 24" fill="none"
stroke="white" stroke-width="1.8" stroke-linecap="round"
stroke-linejoin="round"
style="filter:drop-shadow(0 1px 4px rgba(0,0,0,0.4))">
<path d="M22 2L11 13"/>
<path d="M22 2L15 22L11 13L2 9L22 2Z"/>
</svg>
</button>
<span style="color:#fff;font-size:13px;font-weight:600"
x-text="formatCount(reels[currentIndex]?.shares || 0)"></span>

<div x-show="shareOpen" x-cloak
@keydown.escape.window="shareOpen = false"
style="position:fixed;inset:0;z-index:500;pointer-events:none">

<div @click="shareOpen = false"
style="position:absolute;inset:0;pointer-events:all"></div>

<div @click.stop
style="position:absolute;
bottom:80px;
left:50%;
transform:translateX(-50%);
pointer-events:all;
background:#1c1c1c;
border-radius:16px;
width:360px;
max-height:60vh;
display:flex;
flex-direction:column;
box-shadow:0 20px 60px rgba(0,0,0,0.9);
overflow:hidden">

<div style="padding:16px 16px 12px;
border-bottom:1px solid #2a2a2a;flex-shrink:0">
<h3 style="text-align:center;font-size:16px;
font-weight:700;color:#fff;margin:0 0 12px">
Partager
</h3>
<input type="text" placeholder="Rechercher..."
style="width:100%;background:#2a2a2a;border:none;
border-radius:10px;padding:10px 14px;
color:#fff;font-size:14px;outline:none;
font-family:inherit;box-sizing:border-box">
</div>

<div style="width:100%;flex:1;overflow-y:auto;padding:6px 0">
@foreach(auth()->user()->following()->get() as $friend)
<div @click="toggle({{ $friend->id }})"
:style="isSelected({{ $friend->id }}) ? 'background:#252525' : ''"
style="display:flex !important; flex-direction:row !important; align-items:center !important; justify-content:space-between !important; gap:12px; padding:10px 16px; cursor:pointer; width:100%; box-sizing:border-box; transition:background .1s">

<div style="display:flex !important; flex-direction:row !important; align-items:center !important; gap:12px; flex:1; min-width:0">
<img src="{{ $friend->avatarUrl() }}"
alt="{{ $friend->username }}"
style="width:44px;height:44px;border-radius:50%;
object-fit:cover;flex-shrink:0">

<div style="display:flex !important; flex-direction:column !important; justify-content:center; min-width:0">
<span style="font-weight:700;font-size:14px;color:#fff;line-height:1.3;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
{{ $friend->username }}
</span>
<span style="color:#a8a8a8;font-size:12px;line-height:1.3;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
{{ $friend->name }}
</span>
</div>
</div>

<div :style="isSelected({{ $friend->id }})
? 'background:#0095f6;border-color:#0095f6'
: 'background:transparent;border-color:#555'"
style="width:22px;height:22px;border-radius:50%;
border:2px solid #555;flex-shrink:0;
display:flex;align-items:center;
justify-content:center;transition:all .15s">
<svg x-show="isSelected({{ $friend->id }})"
width="11" height="11" viewBox="0 0 24 24"
fill="none" stroke="white" stroke-width="3"
stroke-linecap="round" stroke-linejoin="round">
<polyline points="20 6 9 17 4 12"/>
</svg>
</div>

</div>
@endforeach
</div>

<div style="padding:12px 16px;border-top:1px solid #2a2a2a;
flex-shrink:0;display:flex;flex-direction:column;gap:8px">
<button @click="copyLink()"
style="width:100%;padding:10px;background:#2a2a2a;
border:none;border-radius:10px;color:#fff;
font-size:14px;font-weight:600;cursor:pointer;
display:flex;align-items:center;
justify-content:center;gap:8px">
🔗 Copier le lien
</button>
<button @click="sendDM()"
:disabled="selectedUsers.length === 0 || sending"
:style="selectedUsers.length > 0 && !sending
? 'opacity:1;cursor:pointer'
: 'opacity:0.4;cursor:default'"
style="width:100%;padding:11px;border:none;
border-radius:10px;background:#0095f6;
color:#fff;font-size:15px;font-weight:700;
transition:opacity .2s">
<span x-show="!sent && !sending">
Envoyer
<span x-show="selectedUsers.length > 0">
(<span x-text="selectedUsers.length"></span>)
</span>
</span>
<span x-show="sending">Envoi...</span>
<span x-show="sent">✓ Envoyé !</span>
</button>
</div>

</div>
</div>

</div>

<div style="display:flex;flex-direction:column;align-items:center;gap:4px">
<button @click="bookmarkReel(currentIndex)"
style="background:none;border:none;cursor:pointer;
padding:6px;display:flex;align-items:center;
justify-content:center">
<svg width="26" height="26" viewBox="0 0 24 24" fill="none"
:fill="reels[currentIndex]?.bookmarked ? 'white' : 'none'"
:stroke="'white'"
stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
style="filter:drop-shadow(0 1px 4px rgba(0,0,0,0.4))">
<path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
</svg>
</button>
</div>

<div x-data="{ moreOpen: false }"
style="display:flex;flex-direction:column;align-items:center;
gap:4px;position:relative">
<button @click="moreOpen = !moreOpen"
style="background:none;border:none;cursor:pointer;padding:6px">
<svg width="24" height="24" viewBox="0 0 24 24" fill="white"
style="filter:drop-shadow(0 1px 4px rgba(0,0,0,0.4))">
<circle cx="12" cy="5"   r="1.5"/>
<circle cx="12" cy="12" r="1.5"/>
<circle cx="12" cy="19" r="1.5"/>
</svg>
</button>
<div x-show="moreOpen" x-cloak
@click.outside="moreOpen = false"
style="position:absolute;right:52px;bottom:0;
background:#1e1e2e;border-radius:14px;
padding:8px 0;min-width:160px;
box-shadow:0 8px 32px rgba(0,0,0,0.7);z-index:50">
<a :href="reels[currentIndex]?.post_url"
style="display:block;padding:12px 16px;font-size:14px;
color:#fff;text-decoration:none">
Voir le post
</a>
<button style="width:100%;background:none;border:none;
color:#ff3040;font-size:14px;padding:12px 16px;
text-align:left;cursor:pointer">
Signaler
</button>
</div>
</div>

<div style="width:36px;height:36px;border-radius:50%;
background:#333;border:2.5px solid #fff;overflow:hidden;
animation:spin-slow 4s linear infinite;flex-shrink:0">
<img :src="reels[currentIndex]?.avatar" alt=""
style="width:100%;height:100%;object-fit:cover">
</div>
</div>

<div style="width:60px;display:flex;flex-direction:column;
align-items:center;justify-content:center;
gap:12px;flex-shrink:0;padding-right:8px">

<button @click="prevReel()"
:disabled="currentIndex === 0"
:style="currentIndex === 0 ? 'opacity:0.3;cursor:default' : 'opacity:1;cursor:pointer'"
style="width:44px;height:44px;border-radius:50%;
background:rgba(255,255,255,0.12);
backdrop-filter:blur(8px);
border:none;display:flex;align-items:center;
justify-content:center;transition:all .2s"
onmouseover="if(this.style.opacity==='1') this.style.background='rgba(255,255,255,0.22)'"
onmouseout="this.style.background='rgba(255,255,255,0.12)'">
<svg width="18" height="18" viewBox="0 0 24 24" fill="none"
stroke="white" stroke-width="2.5" stroke-linecap="round">
<polyline points="18 15 12 9 6 15"/>
</svg>
</button>

<button @click="nextReel()"
:disabled="currentIndex >= reels.length - 1"
:style="currentIndex >= reels.length - 1 ? 'opacity:0.3;cursor:default' : 'opacity:1;cursor:pointer'"
style="width:44px;height:44px;border-radius:50%;
background:rgba(255,255,255,0.12);
backdrop-filter:blur(8px);
border:none;display:flex;align-items:center;
justify-content:center;transition:all .2s"
onmouseover="if(this.style.opacity==='1') this.style.background='rgba(255,255,255,0.22)'"
onmouseout="this.style.background='rgba(255,255,255,0.12)'">
<svg width="18" height="18" viewBox="0 0 24 24" fill="none"
stroke="white" stroke-width="2.5" stroke-linecap="round">
<polyline points="6 9 12 15 18 9"/>
</svg>
</button>
</div>

<div x-show="toastMsg !== ''"
@toast.window="toastMsg = $event.detail; setTimeout(()=> toastMsg='', 2000)"
x-transition:enter="transition ease-out duration-200"
x-transition:enter-start="opacity-0 translate-y-2"
x-transition:enter-end="opacity-100 translate-y-0"
x-transition:leave="transition ease-in duration-200"
x-transition:leave-end="opacity-0"
style="position:fixed;bottom:80px;left:50%;transform:translateX(-50%);
background:#323232;color:#fff;padding:10px 20px;border-radius:20px;
font-size:14px;font-weight:600;z-index:700;white-space:nowrap;
box-shadow:0 4px 16px rgba(0,0,0,0.5)"
x-text="toastMsg">
</div>
</div>

<style>
@keyframes spin-slow { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }
</style>

<script>
function reelsPlayer(reels) {
    return {
        reels: reels.map(r => ({ ...r, followed: false, reposted: false, bookmarked: false })),
        currentIndex: 0,
        isPlaying: false,
        muted: true,
        showPlayIcon: false,
        heartAnim: false,
        progress: {},
        toastMsg: '',

        init() {
            this.$nextTick(() => this.playVideo(0));
            window.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowDown') { e.preventDefault(); this.nextReel(); }
                if (e.key === 'ArrowUp')   { e.preventDefault(); this.prevReel(); }
                if (e.key === ' ')         { e.preventDefault(); this.togglePlay(this.currentIndex); }
                if (e.key === 'm')         this.toggleMute();
            });
        },

        getVideo(i) { return document.getElementById('video-' + i); },

        playVideo(index) {
            this.reels.forEach((_, i) => {
                const v = this.getVideo(i);
                if (v && i !== index) { v.pause(); v.currentTime = 0; }
            });
            const video = this.getVideo(index);
            if (!video) return;
            video.muted = this.muted;
            video.play()
            .then(() => { this.isPlaying = true; this.startProgress(index, video); })
            .catch(() => {
                video.muted = true; this.muted = true;
                video.play().then(() => { this.isPlaying = true; this.startProgress(index, video); });
            });
        },

        startProgress(index, video) {
            const tick = () => {
                if (!video.duration) return;
                this.progress = { ...this.progress, [index]: (video.currentTime / video.duration) * 100 };
                if (this.currentIndex === index && !video.paused) requestAnimationFrame(tick);
            };
                requestAnimationFrame(tick);
        },

        togglePlay(index) {
            const v = this.getVideo(index);
            if (!v) return;
            if (v.paused) { v.play(); this.isPlaying = true; }
            else          { v.pause(); this.isPlaying = false; }
            this.showPlayIcon = true;
            setTimeout(() => this.showPlayIcon = false, 800);
        },

        toggleMute() {
            this.muted = !this.muted;
            const v = this.getVideo(this.currentIndex);
            if (v) v.muted = this.muted;
        },

        nextReel() {
            if (this.currentIndex >= this.reels.length - 1) return;
            this.currentIndex++;
            this.scrollTo(this.currentIndex);
            this.playVideo(this.currentIndex);
        },

        prevReel() {
            if (this.currentIndex <= 0) return;
            this.currentIndex--;
            this.scrollTo(this.currentIndex);
            this.playVideo(this.currentIndex);
        },

        scrollTo(index) {
            const el = document.getElementById('reel-' + index);
            if (el) el.scrollIntoView({ behavior: 'smooth' });
        },

        onScroll(e) {
            const c = e.target;
            const newIndex = Math.round(c.scrollTop / c.clientHeight);
            if (newIndex !== this.currentIndex) {
                this.currentIndex = newIndex;
                this.playVideo(newIndex);
            }
        },

        toast(msg) {
            this.toastMsg = msg;
            setTimeout(() => this.toastMsg = '', 2000);
        },

        async likeReel(index) {
            const reel = this.reels[index];
            this.heartAnim = true;
            setTimeout(() => this.heartAnim = false, 800);
            const wasLiked = reel.liked;
            reel.liked = !wasLiked;
            reel.likes += reel.liked ? 1 : -1;
            try {
                await fetch('/posts/' + reel.id + '/like', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            'Accept': 'application/json',
                    }
                });
            } catch(e) {
                reel.liked = wasLiked;
                reel.likes += wasLiked ? 1 : -1;
            }
        },

        async followUser(index) {
            const reel = this.reels[index];
            const was = reel.followed;
            reel.followed = !was;
            try {
                await fetch('/follow/' + reel.user_id, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            'Accept': 'application/json',
                    }
                });
                this.toast(reel.followed ? '✓ Abonnement ajouté' : 'Abonnement retiré');
            } catch(e) {
                reel.followed = was;
                this.toast('Erreur réseau');
            }
        },

        async repostReel(index) {
            const reel = this.reels[index];
            try {
                const r = await fetch('/repost/' + reel.id, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                      'Accept': 'application/json',
                    }
                });
                const d = await r.json();
                if (d.ok) {
                    reel.reposted = !reel.reposted;
                    reel.reposts += reel.reposted ? 1 : -1;
                    this.toast(reel.reposted ? '✓ Reposté !' : 'Repost retiré');
                }
            } catch(e) { this.toast('Erreur réseau'); }
        },

        async bookmarkReel(index) {
            const reel = this.reels[index];
            const was = reel.bookmarked;
            reel.bookmarked = !was;
            try {
                await fetch('/posts/' + reel.id + '/bookmark', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            'Accept': 'application/json',
                    }
                });
                this.toast(reel.bookmarked ? '✓ Enregistré' : 'Retiré des enregistrements');
            } catch(e) {
                reel.bookmarked = was;
                this.toast('Erreur réseau');
            }
        },

        formatCount(n) {
            if (n >= 1000000) return (n/1000000).toFixed(1) + 'M';
            if (n >= 1000)    return (n/1000).toFixed(1).replace('.',',') + ' k';
            return String(n);
        }
    }
}
</script>
</x-app-layout>
