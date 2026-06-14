<div>
<!-- Liste des Commentaires -->
<div>
@forelse($comments as $comment)
<div style="display:flex;gap:10px;margin-bottom:14px">
<!-- Avatar de l'auteur du commentaire -->
<a href="{{ route('profile.show', $comment->user->username) }}" style="flex-shrink:0">
<img src="{{ $comment->user->avatarUrl() }}" alt=""
style="width:32px;height:32px;border-radius:50%;object-fit:cover">
</a>

<div style="flex:1;min-width:0">
<!-- Pseudo + Texte -->
<div style="font-size:14px;line-height:1.5">
<a href="{{ route('profile.show', $comment->user->username) }}"
style="font-weight:700;color:#fff;margin-right:6px;text-decoration:none">
{{ $comment->user->username }}
</a>{{ $comment->body }}
</div>

<!-- Actions (Date, Répondre, Supprimer) -->
<div style="display:flex;gap:14px;margin-top:5px;align-items:center">
<span style="color:#a8a8a8;font-size:12px">
{{ $comment->created_at->diffForHumans() }}
</span>
<button
wire:click="setReply({{ $comment->id }}, '{{ e($comment->user->username) }}')"
style="color:#a8a8a8;font-size:12px;font-weight:600;background:none;border:none;cursor:pointer;padding:0">
Répondre
</button>
@if(auth()->id() === $comment->user_id || auth()->id() === $postUserId)
<button wire:click="deleteComment({{ $comment->id }})"
style="color:#a8a8a8;font-size:12px;background:none;border:none;cursor:pointer;padding:0">
Supprimer
</button>
@endif
</div>

<!-- Réponses associées à ce commentaire -->
@foreach($comment->replies as $reply)
<div style="display:flex;gap:8px;margin-top:10px">
<img src="{{ $reply->user->avatarUrl() }}" alt=""
style="width:24px;height:24px;border-radius:50%;object-fit:cover;flex-shrink:0">
<div style="flex:1;min-width:0">
<div style="font-size:13px;line-height:1.5">
<span style="font-weight:700;color:#fff;margin-right:6px">
{{ $reply->user->username }}
</span>{{ $reply->body }}
</div>
<div style="display:flex;gap:14px;margin-top:4px">
<span style="color:#a8a8a8;font-size:11px">
{{ $reply->created_at->diffForHumans() }}
</span>
@if(auth()->id() === $reply->user_id || auth()->id() === $postUserId)
<button wire:click="deleteComment({{ $reply->id }})"
style="color:#a8a8a8;font-size:11px;background:none;border:none;cursor:pointer;padding:0">
Supprimer
</button>
@endif
</div>
</div>
</div>
@endforeach
</div>
</div>
@empty
<p style="color:#a8a8a8;font-size:14px;text-align:center;padding:8px 0">
Aucun commentaire. Soyez le premier !
</p>
@endforelse
</div>

<!-- Bandeau d'information quand on répond à quelqu'un -->
@if($replyTo)
<div style="padding:6px 10px;background:#1a1a1a;border-radius:6px;font-size:12px;color:#a8a8a8;margin-bottom:8px;display:flex;justify-content:space-between;align-items:center">
<span>Réponse à <strong style="color:#fff">{{ $replyUsername }}</strong></span>
<button wire:click="cancelReply"
style="color:#a8a8a8;background:none;border:none;cursor:pointer;font-size:18px;line-height:1;padding:0 4px">×</button>
</div>
@endif

<!-- Champ de saisie (Nouveau commentaire ou Réponse) -->
<div style="display:flex;align-items:center;gap:10px;border-top:1px solid #262626;padding-top:10px">
<img src="{{ auth()->user()->avatarUrl() }}" alt=""
style="width:28px;height:28px;border-radius:50%;object-fit:cover;flex-shrink:0">
<input
wire:model="body"
wire:keydown.enter="submit"
placeholder="{{ $replyTo ? 'Répondre à ' . $replyUsername . '...' : 'Ajouter un commentaire...' }}"
style="flex:1;background:transparent;border:none;color:#fff;font-size:14px;outline:none;font-family:inherit;caret-color:#fff">

@if($body && trim($body) !== '')
<button wire:click="submit"
style="color:#0095f6;font-size:14px;font-weight:700;background:none;border:none;cursor:pointer;white-space:nowrap;padding:0">
Publier
</button>
@endif
</div>
</div>
