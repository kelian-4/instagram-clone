<div>
<div x-data x-on:open-create-post.window="$wire.openModal()" style="display:none"></div>

@if($open)
<div style="position:fixed;inset:0;background:rgba(0,0,0,0.85);z-index:500;
display:flex;align-items:center;justify-content:center;padding:20px"
x-data
@keydown.escape.window="$wire.closeModal()">

<div style="background:#262626;border-radius:12px;width:100%;
max-width:{{ $step === 2 ? '920px' : '520px' }};
overflow:hidden;box-shadow:0 8px 40px rgba(0,0,0,0.9);
max-height:90vh;display:flex;flex-direction:column">

<!-- Header -->
<div style="display:flex;align-items:center;justify-content:space-between;
padding:12px 16px;border-bottom:1px solid #363636;flex-shrink:0">

@if($step === 2)
{{-- Retour étape 1 --}}
<button wire:click="$set('step',1)"
style="background:none;border:none;cursor:pointer;padding:4px;
display:flex;align-items:center;color:#fff">
<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
stroke="white" stroke-width="2" stroke-linecap="round">
<polyline points="15 18 9 12 15 6"/>
</svg>
</button>
@else
{{-- Bouton Annuler étape 1 --}}
<button wire:click="closeModal"
style="background:none;border:none;cursor:pointer;
padding:4px;display:flex;color:#fff">
<svg width="20" height="20" viewBox="0 0 24 24" fill="none"
stroke="white" stroke-width="2.5" stroke-linecap="round">
<line x1="18" y1="6" x2="6" y2="18"/>
<line x1="6" y1="6" x2="18" y2="18"/>
</svg>
</button>
@endif

<span style="font-weight:700;font-size:16px;color:#fff">Créer une publication</span>

@if($step === 2)
{{-- Bouton Partager --}}
<button wire:click="publish"
style="background:none;border:none;cursor:pointer;
color:#0095f6;font-size:14px;font-weight:700;padding:4px 0;
opacity:1;transition:opacity .2s"
wire:loading.attr="disabled"
onmousedown="this.style.opacity='0.6'"
onmouseup="this.style.opacity='1'">
<span wire:loading.remove wire:target="publish">Partager</span>
<span wire:loading wire:target="publish" style="color:#a8a8a8">Envoi...</span>
</button>
@else
<div style="width:40px"></div>
@endif
</div>

<!-- Contenu dynamique selon l'étape -->
@if($step === 1)
<!-- ══ ÉTAPE 1 : Sélection des fichiers ══ -->
<div style="flex:1"
x-data="{
dragging: false,
handleDrop(e) {
this.dragging = false;
const files = Array.from(e.dataTransfer.files);
if (!files.length) return;
const input = document.getElementById('media-upload-input');
const dt = new DataTransfer();
files.forEach(f => dt.items.add(f));
input.files = dt.files;
input.dispatchEvent(new Event('change'));
}
}"
@dragover.prevent="dragging = true"
@dragleave.prevent="dragging = false"
@drop.prevent="handleDrop($event)">

<div style="height:420px;
display:flex;
flex-direction:column;
align-items:center;
justify-content:center;
gap:0;
text-align:center;
padding:0 40px;
transition:background .2s"
:style="dragging ? 'background:rgba(0,149,246,0.06)' : ''">

<!-- Icône -->
<svg width="80" height="80" viewBox="0 0 24 24" fill="none"
stroke="#a8a8a8" stroke-width="1"
style="margin-bottom:20px;display:block;flex-shrink:0"
:style="dragging ? 'stroke:#0095f6;transform:scale(1.1)' : 'transform:scale(1)'">
<rect x="3" y="3" width="18" height="18" rx="3"/>
<circle cx="8.5" cy="8.5" r="1.5" fill="#a8a8a8" stroke="none"/>
<polyline points="21 15 16 10 5 21"/>
</svg>

<!-- Titre -->
<p style="color:#fff;font-size:20px;font-weight:300;
margin:0 0 8px;line-height:1.3">
Glissez-déposez vos photos et vidéos
</p>

<!-- Sous-titre -->
<p style="color:#a8a8a8;font-size:14px;margin:0 0 28px">
JPG, PNG, GIF, WEBP, MP4 — Max 50 Mo
</p>

<!-- Erreur -->
@error('media')
<p style="color:#ff3040;font-size:13px;margin-bottom:16px;
background:rgba(255,48,64,0.1);padding:8px 20px;
border-radius:8px;display:inline-block">
{{ $message }}
</p>
@enderror

<!-- Aperçus si fichiers sélectionnés -->
@if(!empty($media))
<div style="display:flex;gap:8px;flex-wrap:wrap;
justify-content:center;margin-bottom:20px">
@foreach($media as $i => $m)
<div style="position:relative">
<img src="{{ $m->temporaryUrl() }}" alt=""
style="width:80px;height:80px;object-fit:cover;
border-radius:8px;border:2px solid #555">
<div style="position:absolute;top:3px;right:3px;
background:rgba(0,0,0,0.65);border-radius:50%;
width:18px;height:18px;display:flex;align-items:center;
justify-content:center;font-size:10px;color:#fff">
{{ $i + 1 }}
</div>
</div>
@endforeach
</div>
<button wire:click="nextStep"
style="background:#0095f6;color:#fff;padding:9px 32px;
border-radius:8px;font-size:14px;font-weight:600;
border:none;cursor:pointer;margin-bottom:16px;display:block">
Suivant →
</button>
@endif

<!-- Bouton sélectionner -->
<label style="display:inline-block;background:#0095f6;color:#fff;
padding:9px 24px;border-radius:8px;font-size:14px;
font-weight:600;cursor:pointer">
Sélectionner sur l'appareil
<input id="media-upload-input"
type="file"
wire:model="media"
multiple
accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,video/mp4,video/quicktime"
style="display:none">
</label>
</div>
</div>
@else
<!-- ══ ÉTAPE 2 : Caption + options ══ -->
<div style="display:flex;flex:1;overflow:hidden;max-height:calc(90vh - 53px)">

<!-- Aperçu Gauche -->
<div style="flex:0 0 55%;background:#000;display:flex;
align-items:center;justify-content:center;overflow:hidden">
@if(!empty($media))
@if(count($media) > 1)
<div x-data="{ cur:0, tot:{{ count($media) }} }"
style="position:relative;width:100%;height:100%">
@foreach($media as $i => $m)
<img src="{{ $m->temporaryUrl() }}" alt=""
x-show="cur === {{ $i }}"
style="width:100%;height:100%;object-fit:contain;display:block">
@endforeach
<button x-show="cur>0" @click="cur--"
style="position:absolute;left:10px;top:50%;
transform:translateY(-50%);width:32px;height:32px;
border-radius:50%;background:rgba(255,255,255,0.85);
border:none;cursor:pointer;display:flex;
align-items:center;justify-content:center">
<svg width="14" height="14" viewBox="0 0 24 24" fill="none"
stroke="#000" stroke-width="2.5">
<polyline points="15 18 9 12 15 6"/>
</svg>
</button>
<button x-show="cur<tot-1" @click="cur++"
style="position:absolute;right:10px;top:50%;
transform:translateY(-50%);width:32px;height:32px;
border-radius:50%;background:rgba(255,255,255,0.85);
border:none;cursor:pointer;display:flex;
align-items:center;justify-content:center">
<svg width="14" height="14" viewBox="0 0 24 24" fill="none"
stroke="#000" stroke-width="2.5">
<polyline points="9 18 15 12 9 6"/>
</svg>
</button>
<div style="position:absolute;bottom:12px;left:50%;
transform:translateX(-50%);display:flex;gap:4px">
<template x-for="i in tot" :key="i">
<div :style="'width:6px;height:6px;border-radius:50%;transition:background .2s;background:'+(cur===i-1?'#fff':'rgba(255,255,255,0.4)')"></div>
</template>
</div>
</div>
@else
<img src="{{ $media[0]->temporaryUrl() }}" alt=""
style="max-width:100%;max-height:100%;object-fit:contain;display:block">
@endif
@endif
</div>

<!-- Panneau Droite -->
<div style="flex:0 0 45%;display:flex;flex-direction:column;
border-left:1px solid #363636;overflow-y:auto">

<!-- User -->
<div style="display:flex;align-items:center;gap:10px;
padding:14px 16px;flex-shrink:0">
<img src="{{ auth()->user()->avatarUrl() }}" alt=""
style="width:32px;height:32px;border-radius:50%;object-fit:cover">
<span style="font-weight:600;font-size:14px;color:#fff">
{{ auth()->user()->username }}
</span>
</div>

<!-- Caption -->
<div style="padding:0 16px;flex-shrink:0">
<textarea wire:model.live="caption"
placeholder="Rédigez une légende..."
maxlength="2200"
style="width:100%;background:transparent;border:none;
color:#fff;font-size:15px;resize:none;outline:none;
line-height:1.6;font-family:inherit;min-height:140px"></textarea>
<div style="display:flex;justify-content:space-between;
border-top:1px solid #363636;padding:6px 0;
color:#a8a8a8;font-size:12px">
<span style="cursor:pointer">😊</span>
<span>{{ strlen($caption) }}/2 200</span>
</div>
</div>

<!-- Lieu -->
<div style="border-top:1px solid #363636;padding:12px 16px;
display:flex;align-items:center;gap:10px;flex-shrink:0">
<input type="text" wire:model="location"
placeholder="Ajouter un lieu"
style="flex:1;background:transparent;border:none;
color:#fff;font-size:15px;outline:none;font-family:inherit">
<svg width="20" height="20" viewBox="0 0 24 24" fill="none"
stroke="#a8a8a8" stroke-width="1.8">
<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
<circle cx="12" cy="10" r="3"/>
</svg>
</div>

<!-- Accessibilité -->
<div style="border-top:1px solid #363636;flex-shrink:0">
<button wire:click="$toggle('showAltSection')"
style="width:100%;background:none;border:none;cursor:pointer;
padding:14px 16px;display:flex;align-items:center;
justify-content:space-between;color:#fff">
<span style="font-size:15px">Accessibilité</span>
<svg width="18" height="18" viewBox="0 0 24 24" fill="none"
stroke="#a8a8a8" stroke-width="2"
style="transition:transform .2s;
transform:rotate({{ $showAltSection ? '180' : '0' }}deg)">
<polyline points="6 9 12 15 18 9"/>
</svg>
</button>

@if($showAltSection)
<div style="padding:0 16px 16px">
<p style="color:#a8a8a8;font-size:13px;margin-bottom:12px;line-height:1.5">
Le texte alternatif décrit vos photos pour les personnes malvoyantes.
</p>
@foreach($media as $i => $m)
<div style="display:flex;align-items:center;gap:10px;margin-bottom:8px">
<img src="{{ $m->temporaryUrl() }}" alt=""
style="width:44px;height:44px;object-fit:cover;
border-radius:4px;flex-shrink:0">
<input type="text"
wire:model="altTexts.{{ $i }}"
placeholder="Texte alternatif..."
style="flex:1;background:#363636;border:none;
border-radius:6px;padding:8px 10px;
color:#fff;font-size:13px;outline:none;
font-family:inherit">
</div>
@endforeach
</div>
@endif
</div>

<!-- Paramètres avancés -->
<div style="border-top:1px solid #363636;flex-shrink:0">
<button wire:click="$toggle('showAdvanced')"
style="width:100%;background:none;border:none;cursor:pointer;
padding:14px 16px;display:flex;align-items:center;
justify-content:space-between;color:#fff">
<span style="font-size:15px">Paramètres avancés</span>
<svg width="18" height="18" viewBox="0 0 24 24" fill="none"
stroke="#a8a8a8" stroke-width="2"
style="transition:transform .2s;
transform:rotate({{ $showAdvanced ? '180' : '0' }}deg)">
<polyline points="6 9 12 15 18 9"/>
</svg>
</button>

@if($showAdvanced)
<div style="padding:0 16px 16px">
<!-- Désactiver commentaires -->
<div style="display:flex;align-items:center;
justify-content:space-between;
padding:12px 0;border-bottom:1px solid #363636">
<div style="flex:1;padding-right:16px">
<div style="font-size:15px;color:#fff;margin-bottom:3px">
Désactiver les commentaires
</div>
<div style="font-size:12px;color:#a8a8a8;line-height:1.4">
Les commentaires seront désactivés pour cette publication.
</div>
</div>
<div x-data="{ on: @entangle('disableComments') }"
@click="on = !on"
:style="'cursor:pointer;width:44px;height:26px;border-radius:13px;padding:3px;transition:background .25s;background:'+(on?'#0095f6':'#555')">
<div :style="'width:20px;height:20px;border-radius:50%;background:#fff;transition:transform .25s;transform:'+(on?'translateX(18px)':'translateX(0px)')"></div>
</div>
</div>

<!-- Masquer les J'aime -->
<div style="display:flex;align-items:center;
justify-content:space-between;padding:12px 0">
<div style="flex:1;padding-right:16px">
<div style="font-size:15px;color:#fff;margin-bottom:3px">
Masquer le nombre de J'aime
</div>
<div style="font-size:12px;color:#a8a8a8;line-height:1.4">
Seul vous pouvez voir le total des J'aime et des vues de cette publication.
</div>
</div>
<div x-data="{ on: @entangle('hideLikes') }"
@click="on = !on"
:style="'cursor:pointer;width:44px;height:26px;border-radius:13px;padding:3px;transition:background .25s;background:'+(on?'#0095f6':'#555')">
<div :style="'width:20px;height:20px;border-radius:50%;background:#fff;transition:transform .25s;transform:'+(on?'translateX(18px)':'translateX(0px)')"></div>
</div>
</div>
</div>
@endif
</div>

@error('media')
<div style="padding:10px 16px;color:#ff3040;font-size:13px;
background:rgba(255,48,64,0.1);flex-shrink:0">
{{ $message }}
</div>
@enderror

</div>
</div>
@endif
</div>
</div>
@endif
<style>
@keyframes spin { to { transform: rotate(360deg); } }
</style>
</div>
