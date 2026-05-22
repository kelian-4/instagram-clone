<x-app-layout>
<div style="max-width:935px;margin:40px auto;padding:0 20px">

    <!-- Modal-style layout: image gauche + panel droit -->
    <div style="display:flex;background:#000;border:1px solid #262626;
                border-radius:4px;overflow:hidden;min-height:600px">

        <!-- ── Colonne image (60%) ── -->
        <div style="flex:0 0 60%;background:#111;display:flex;align-items:center;justify-content:center">
            @if($post->media->count() > 1)
            <div class="ig-carousel" style="width:100%;height:100%;max-height:600px"
                 x-data="{ current: 0, total: {{ $post->media->count() }} }">
                <div class="ig-carousel-track" style="height:100%"
                     :style="'transform:translateX(-' + (current * 100) + '%)'">
                    @foreach($post->media as $media)
                    <div class="ig-carousel-slide">
                        <img src="{{ $media->url() }}" alt=""
                             style="width:100%;height:100%;object-fit:contain;max-height:600px">
                    </div>
                    @endforeach
                </div>
                <button class="ig-carousel-btn prev" x-show="current > 0" @click="current--">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                         stroke="white" stroke-width="3"><polyline points="15 18 9 12 15 6"/></svg>
                </button>
                <button class="ig-carousel-btn next" x-show="current < total - 1" @click="current++">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                         stroke="white" stroke-width="3"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
                <div class="ig-carousel-dots">
                    <template x-for="i in total" :key="i">
                        <div class="ig-carousel-dot" :class="{ active: current === i - 1 }"></div>
                    </template>
                </div>
            </div>
            @elseif($post->media->count() === 1)
            @php $m = $post->media->first(); @endphp
            @if($m->type === 'video')
            <video src="{{ $m->url() }}" controls autoplay muted
                   style="max-width:100%;max-height:600px;object-fit:contain"></video>
            @else
            <img src="{{ $m->url() }}" alt=""
                 style="max-width:100%;max-height:600px;object-fit:contain;display:block">
            @endif
            @endif
        </div>

        <!-- ── Panneau droit (40%) ── -->
        <div style="flex:0 0 40%;display:flex;flex-direction:column;
                    border-left:1px solid #262626;max-height:600px">

            <!-- Header -->
            <div style="display:flex;align-items:center;gap:10px;
                        padding:14px 16px;border-bottom:1px solid #262626;flex-shrink:0">
                <a href="{{ route('profile.show', $post->user->username) }}">
                    <div class="ig-avatar-ring {{ $post->user->hasActiveStory() ? 'has-story' : '' }}"
                         style="width:34px;height:34px">
                        <img src="{{ $post->user->avatarUrl() }}" alt=""
                             class="ig-avatar-img">
                    </div>
                </a>
                <div style="flex:1">
                    <a href="{{ route('profile.show', $post->user->username) }}"
                       style="font-weight:700;font-size:14px;color:#fff">
                        {{ $post->user->username }}
                    </a>
                    @if($post->location)
                    <div style="font-size:12px;color:#a8a8a8">{{ $post->location }}</div>
                    @endif
                </div>
                @if(auth()->id() === $post->user_id)
                <form action="{{ route('posts.destroy', $post) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" style="color:#ff3040;font-size:12px;background:none;border:none;cursor:pointer">
                        Supprimer
                    </button>
                </form>
                @endif
            </div>

            <!-- Caption + commentaires scrollables -->
            <div style="flex:1;overflow-y:auto;padding:16px">

                <!-- Caption -->
                @if($post->caption)
                <div style="display:flex;gap:10px;margin-bottom:16px">
                    <img src="{{ $post->user->avatarUrl() }}" alt=""
                         style="width:32px;height:32px;border-radius:50%;object-fit:cover;flex-shrink:0">
                    <div>
                        <span style="font-weight:700;font-size:14px;margin-right:6px">
                            {{ $post->user->username }}
                        </span>
                        <span style="font-size:14px">{!! $post->parsedCaption() !!}</span>
                        <div style="color:#a8a8a8;font-size:11px;margin-top:4px">
                            {{ $post->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
                @endif

                <!-- Commentaires Livewire -->
                @livewire('comment-section', ['post' => $post])
            </div>

            <!-- Actions -->
            <div style="border-top:1px solid #262626;padding:10px 16px;flex-shrink:0">
                <div class="ig-post-actions">
                    @livewire('like-button', ['post' => $post], key('like-show-'.$post->id))

                    <!-- Commentaire -->
                    <button onclick="document.querySelector('input[placeholder*=\"commentaire\"]').focus()"
                            style="padding:4px">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.656 17.008a9.993 9.993 0 1 0-3.59 3.615L22 22Z"/>
                        </svg>
                    </button>

                    <!-- Partage -->
                    <button style="padding:4px">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12.003 2.001a9.705 9.705 0 1 1 0 19.41 9.705 9.705 0 0 1 0-19.41Z"/>
                            <path d="M2.84 9.508 21.158 2.84 14.49 21.161l-3.476-7.674Z"/>
                        </svg>
                    </button>

                    <div style="flex:1"></div>
                    @livewire('bookmark-button', ['post' => $post], key('bm-show-'.$post->id))
                </div>

                <div style="font-size:14px;font-weight:700;margin-bottom:4px">
                    {{ number_format($post->likes_count) }} J'aime
                </div>
                <div style="color:#a8a8a8;font-size:11px">
                    {{ strtoupper($post->created_at->format('d F Y')) }}
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
