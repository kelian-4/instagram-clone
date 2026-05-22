<div wire:poll.3000ms="loadMessages"
style="display:flex;flex-direction:column;
height:calc(100vh - 77px)">

<!-- Zone messages — flex-direction:column (plus récents en BAS) -->
<div id="dm-msgs"
style="flex:1;overflow-y:auto;padding:20px 24px;
display:flex;flex-direction:column;gap:4px"
x-data
x-init="
const el = document.getElementById('dm-msgs');
// Scroll vers le bas immédiatement
el.scrollTop = el.scrollHeight;
// Re-scroll à chaque mise à jour
const obs = new MutationObserver(() => {
el.scrollTop = el.scrollHeight;
});
obs.observe(el, { childList: true, subtree: true });
">

@forelse($messages as $i => $msg)

{{-- Date séparatrice si jour différent du précédent --}}
@if($i === 0 || \Carbon\Carbon::parse($messages[$i-1]['created_at'])->format('Y-m-d') !== \Carbon\Carbon::parse($msg['created_at'])->format('Y-m-d'))
<div style="text-align:center;margin:12px 0 8px">
<span style="color:#a8a8a8;font-size:12px;background:#1a1a2a;
padding:3px 10px;border-radius:10px">
{{ \Carbon\Carbon::parse($msg['created_at'])->format('d/m/Y') }}
</span>
</div>
@endif

<div style="display:flex;flex-direction:column;
align-items:{{ $msg['mine'] ? 'flex-end' : 'flex-start' }};
gap:2px">
@if(!$msg['mine'])
<div style="display:flex;align-items:flex-end;gap:8px">
<img src="{{ $msg['avatar'] }}" alt=""
style="width:26px;height:26px;border-radius:50%;
object-fit:cover;flex-shrink:0">
<div style="max-width:65%;padding:10px 14px;border-radius:18px;
border-bottom-left-radius:4px;background:#1e1e2e;
color:#fff;font-size:14px;line-height:1.5;
word-break:break-word">
{{ $msg['body'] }}
</div>
</div>
@else
<div style="max-width:65%;padding:10px 14px;border-radius:18px;
border-bottom-right-radius:4px;background:#0095f6;
color:#fff;font-size:14px;line-height:1.5;
word-break:break-word">
{{ $msg['body'] }}
</div>
@endif
<span style="color:#a8a8a8;font-size:10px;padding:0 4px">
{{ $msg['created_at'] }}
@if($msg['mine'] && $msg['read_at'])
· <span style="color:#0095f6">Vu</span>
@endif
</span>
</div>

@empty
<div style="flex:1;display:flex;flex-direction:column;align-items:center;
justify-content:center;gap:12px;color:#a8a8a8;text-align:center;
padding:40px">
<svg width="56" height="56" viewBox="0 0 24 24" fill="none"
stroke="#a8a8a8" stroke-width="1" stroke-linecap="round">
<path d="M22 2L11 13"/><path d="M22 2L15 22L11 13L2 9L22 2Z"/>
</svg>
<p style="font-size:14px;line-height:1.5">
Aucun message pour l'instant.<br>Envoyez le premier !
</p>
</div>
@endforelse
</div>

<!-- Saisie -->
<div style="padding:10px 16px;border-top:1px solid #1e1e2e;
display:flex;align-items:center;gap:10px;
background:#0e0e18;flex-shrink:0">
<button style="font-size:20px;opacity:0.7;padding:2px"
onmouseover="this.style.opacity='1'"
onmouseout="this.style.opacity='0.7'">😊</button>

<div style="flex:1;background:#1e1e2e;border-radius:22px;
padding:9px 14px;display:flex;align-items:center">
<input type="text"
wire:model="body"
wire:keydown.enter="send"
placeholder="Message..."
style="flex:1;background:transparent;border:none;
color:#fff;font-size:14px;outline:none;
font-family:inherit;caret-color:#fff">
</div>

@if(strlen($body) > 0)
<button wire:click="send"
style="color:#0095f6;font-size:14px;font-weight:700;
padding:4px;white-space:nowrap">
Envoyer
</button>
@else
<button style="padding:4px;opacity:0.7"
onmouseover="this.style.opacity='1'"
onmouseout="this.style.opacity='0.7'">
<svg width="22" height="22" viewBox="0 0 24 24" fill="none"
stroke="white" stroke-width="1.8" stroke-linecap="round">
<rect x="3" y="3" width="18" height="18" rx="3"/>
<circle cx="8.5" cy="8.5" r="1.5"/>
<polyline points="21 15 16 10 5 21"/>
</svg>
</button>
<button style="padding:4px;opacity:0.7"
onmouseover="this.style.opacity='1'"
onmouseout="this.style.opacity='0.7'">
<svg width="22" height="22" viewBox="0 0 24 24" fill="none"
stroke="white" stroke-width="1.8" stroke-linecap="round">
<path d="M22 2L11 13"/>
<path d="M22 2L15 22L11 13L2 9L22 2Z"/>
</svg>
</button>
@endif
</div>
</div>
