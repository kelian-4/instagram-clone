<div>
@if($open && !empty($userStories))
<div class="ig-story-modal" wire:click.self="close">
<div class="ig-story-container"
x-data="storyTimer({{ count($userStories) }}, {{ $storyIndex }})"
x-init="start()"
@keydown.arrowleft.window="$wire.prev(); resetTimer()"
@keydown.arrowright.window="$wire.next(); resetTimer()"
@keydown.escape.window="$wire.close()">

<!-- Barres de progression -->
<div class="ig-story-progress">
@foreach($userStories as $i => $s)
<div class="ig-story-progress-bar">
<div class="ig-story-progress-fill"
:style="'width:' + ({{ $i }} < {{ $storyIndex }} ? '100' : ({{ $i }} === {{ $storyIndex }} ? progress : '0')) + '%'">
</div>
</div>
@endforeach
</div>

<!-- Header -->
<div style="position:absolute;top:20px;left:12px;right:40px;
display:flex;align-items:center;gap:10px;z-index:10">
<img src="{{ $userStories[$storyIndex]['avatar'] }}" alt=""
style="width:36px;height:36px;border-radius:50%;border:2px solid #fff;object-fit:cover">
<span style="font-weight:600;font-size:14px;color:#fff">
{{ $userStories[$storyIndex]['username'] }}
</span>
<span style="color:rgba(255,255,255,0.6);font-size:12px">
{{ $userStories[$storyIndex]['created_at'] }}
</span>
</div>

<!-- Bouton fermer -->
<button wire:click="close"
style="position:absolute;top:20px;right:12px;z-index:10;
background:none;border:none;cursor:pointer">
<svg width="20" height="20" viewBox="0 0 24 24" fill="none"
stroke="white" stroke-width="2" stroke-linecap="round">
<line x1="18" y1="6" x2="6" y2="18"/>
<line x1="6" y1="6" x2="18" y2="18"/>
</svg>
</button>

<!-- Média -->
@if($userStories[$storyIndex]['type'] === 'video')
<video src="{{ $userStories[$storyIndex]['url'] }}"
autoplay muted playsinline
style="width:100%;height:100%;object-fit:cover">
</video>
@else
<img src="{{ $userStories[$storyIndex]['url'] }}" alt=""
style="width:100%;height:100%;object-fit:cover">
@endif

<!-- Zones clic prev/next -->
<div style="position:absolute;inset:0;display:flex">
<div style="flex:1;cursor:pointer" wire:click="prev" @click="resetTimer()"></div>
<div style="flex:1;cursor:pointer" wire:click="next" @click="resetTimer()"></div>
</div>
</div>
</div>

<script>
function storyTimer(total, current) {
    return {
        progress: 0,
        timer: null,
        start() {
            this.progress = 0;
            clearInterval(this.timer);
            this.timer = setInterval(() => {
                this.progress += 100 / (5000 / 50); // 5s par story
                if (this.progress >= 100) {
                    clearInterval(this.timer);
                    this.$wire.next();
                }
            }, 50);
        },
        resetTimer() {
            this.start();
        }
    }
}
</script>
@endif
</div>
