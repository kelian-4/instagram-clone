<div>
    <button wire:click="toggle" style="background:none;border:none;cursor:pointer;padding:4px">
        @if($saved)
            <svg width="24" height="24" viewBox="0 0 24 24" fill="white" stroke="white" stroke-width="1.8">
                <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
            </svg>
        @else
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8">
                <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
            </svg>
        @endif
    </button>
</div>
