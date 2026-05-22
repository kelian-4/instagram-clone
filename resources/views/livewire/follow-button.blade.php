<div>
    @if(auth()->id() !== $target->id)
        <button wire:click="toggle"
                class="{{ $following ? 'ig-btn-unfollow' : 'ig-btn-follow' }}">
            {{ $following ? 'Abonné' : 'Suivre' }}
        </button>
    @endif
</div>
