<div style="display:flex;align-items:center;gap:6px">

<button wire:click="toggle"
style="background:none;border:none;cursor:pointer;
padding:6px;display:flex;align-items:center;outline:none">
@if($liked)
{{-- Cœur PLEIN rouge --}}
<svg width="24" height="24" viewBox="0 0 24 24"
xmlns="http://www.w3.org/2000/svg"
style="display:block">
<path fill="#ff3040"
d="M12 21.593c-.169-.1-10-6.112-10-12.47C2 5.228 4.548 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.452 3 22 5.228 22 9.122c0 6.358-9.831 12.37-10 12.471z"/>
</svg>
@else
{{-- Cœur VIDE blanc --}}
<svg width="24" height="24" viewBox="0 0 24 24"
xmlns="http://www.w3.org/2000/svg"
fill="none" stroke="white" stroke-width="1.8"
stroke-linecap="round" stroke-linejoin="round"
style="display:block">
<path d="M16.792 3.904A4.989 4.989 0 0 1 21.5 9.122c0 3.517-4.903 7.574-9.5 10.378-4.597-2.804-9.5-6.861-9.5-10.378a4.989 4.989 0 0 1 4.708-5.218 4.21 4.21 0 0 1 3.675 1.941c.84 1.175.98 1.763 1.12 1.763s.278-.588 1.11-1.766a4.17 4.17 0 0 1 3.679-1.938m0-2a6.04 6.04 0 0 0-4.797 2.127 6.052 6.052 0 0 0-4.787-2.127A6.985 6.985 0 0 0 .5 9.122c0 3.61 2.55 7.097 7.16 10.124a50.153 50.153 0 0 0 4.34 2.555 50.154 50.154 0 0 0 4.342-2.555C20.95 16.22 23.5 12.733 23.5 9.122a6.985 6.985 0 0 0-6.708-7.218Z"/>
</svg>
@endif
</button>

{{-- Compteur mis à jour par Livewire --}}
<span style="font-size:14px;font-weight:700;color:#fff">
{{ number_format($likesCount) }}
</span>
</div>
