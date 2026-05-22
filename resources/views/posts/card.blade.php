<article class="mb-6 border-b border-neutral-800 pb-2">

    {{-- Header --}}
    <div class="flex items-center justify-between px-0 py-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('profile.show', $post->user->username) }}">
                <div class="story-ring">
                    <div class="bg-black p-0.5 rounded-full">
                        <img src="{{ $post->user->avatarUrl() }}" alt=""
                             class="w-8 h-8 rounded-full object-cover">
                    </div>
                </div>
            </a>
            <div>
                <a href="{{ route('profile.show', $post->user->username) }}"
                   class="font-semibold text-sm text-white hover:underline">
                    {{ $post->user->username }}
                </a>
                @if($post->location)
                <p class="text-xs text-neutral-400">{{ $post->location }}</p>
                @endif
            </div>
            <span class="text-neutral-500 text-xs">• {{ $post->created_at->diffForHumans(null, true) }}</span>
        </div>
        <button class="text-white px-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
        </button>
    </div>

    {{-- Image --}}
    <div class="relative bg-black w-full">
        @php $images = $post->images; @endphp
        @if($images->count() > 1)
        <div x-data="{ current: 0 }" class="relative">
            @foreach($images as $i => $image)
            <img src="{{ $image->url() }}" alt=""
                 x-show="current === {{ $i }}"
                 class="w-full object-cover max-h-[585px]">
            @endforeach
            <button @click="current = Math.max(0, current - 1)" x-show="current > 0"
                    class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/50 rounded-full p-1.5">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button @click="current = Math.min({{ $images->count()-1 }}, current + 1)" x-show="current < {{ $images->count()-1 }}"
                    class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/50 rounded-full p-1.5">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M9 5l7 7-7 7"/></svg>
            </button>
            <div class="absolute bottom-3 left-0 right-0 flex justify-center gap-1">
                @foreach($images as $i => $image)
                <div :class="current === {{ $i }} ? 'bg-blue-500' : 'bg-white/50'"
                     class="w-1.5 h-1.5 rounded-full transition-all"></div>
                @endforeach
            </div>
        </div>
        @else
        <img src="{{ $post->thumbnailUrl() }}" alt="" class="w-full object-cover max-h-[585px]">
        @endif
    </div>

    {{-- Actions --}}
    <div class="pt-3 px-0">
        <div class="flex items-center justify-between mb-1">
            <div class="flex items-center gap-4">
                <livewire:like-button :post="$post" :key="'like-'.$post->id" />
                <a href="{{ route('posts.show', $post) }}" class="hover:opacity-60 transition">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 0 1 1.037-.443 48.282 48.282 0 0 0 5.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/>
                    </svg>
                </a>
                <button class="hover:opacity-60 transition">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5"/>
                    </svg>
                </button>
            </div>
            <button class="hover:opacity-60 transition">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0Z"/>
                </svg>
            </button>
        </div>

        {{-- Likes --}}
        <p class="font-semibold text-sm text-white">{{ number_format($post->likes_count) }} J'aime</p>

        {{-- Caption --}}
        @if($post->caption)
        <p class="text-sm text-white mt-1">
            <a href="{{ route('profile.show', $post->user->username) }}" class="font-semibold hover:underline">{{ $post->user->username }}</a>
            <span class="ml-1 text-white">{{ Str::limit($post->caption, 100) }}</span>
        </p>
        @endif

        {{-- Lien commentaires --}}
        @if($post->comments_count > 0)
        <a href="{{ route('posts.show', $post) }}" class="text-sm text-neutral-400 mt-1 block hover:text-neutral-300">
            Voir les {{ $post->comments_count }} commentaires
        </a>
        @endif

        {{-- Commentaire rapide --}}
        @auth
        <div class="mt-2">
            <livewire:comment-section :post="$post" :key="'comment-'.$post->id" />
        </div>
        @endauth
    </div>
</article>
