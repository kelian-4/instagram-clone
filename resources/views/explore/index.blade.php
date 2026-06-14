<x-app-layout>
<div style="max-width:935px;margin:0 auto;padding:20px">

    <!-- Barre de recherche -->
    <div style="max-width:400px;margin:0 auto 24px;position:relative">
        <svg style="position:absolute;left:14px;top:50%;transform:translateY(-50%)"
             width="16" height="16" viewBox="0 0 24 24" fill="none"
             stroke="#a8a8a8" stroke-width="2">
            <path d="M19 10.5A8.5 8.5 0 1 1 10.5 2a8.5 8.5 0 0 1 8.5 8.5Z"/>
            <line x1="16.511" y1="16.511" x2="22" y2="22"/>
        </svg>
        <form action="{{ route('explore') }}" method="GET">
            <input type="text" name="q" value="{{ $query }}"
                   placeholder="Rechercher"
                   style="width:100%;background:#1e1e2e;border:none;border-radius:10px;
                          padding:10px 10px 10px 42px;color:#fff;font-size:14px;
                          outline:none;font-family:inherit">
        </form>
    </div>

    <!-- Résultats users -->
    @if($users->count() > 0)
    <div style="margin-bottom:24px">
        <h3 style="font-size:14px;font-weight:700;color:#a8a8a8;
                   margin-bottom:12px;text-transform:uppercase;letter-spacing:0.5px">
            Comptes
        </h3>
        <div style="display:flex;flex-wrap:wrap;gap:12px">
            @foreach($users as $u)
            <a href="{{ route('profile.show', $u->username) }}"
               style="display:flex;align-items:center;gap:10px;background:#1e1e2e;
                      border-radius:12px;padding:12px 16px;text-decoration:none;
                      color:#fff;min-width:200px;transition:background 0.15s"
               onmouseover="this.style.background='#252535'"
               onmouseout="this.style.background='#1e1e2e'">
                <img src="{{ $u->avatarUrl() }}" alt="{{ $u->username }}"
                     style="width:44px;height:44px;border-radius:50%;object-fit:cover">
                <div>
                    <div style="font-weight:700;font-size:14px">{{ $u->username }}</div>
                    <div style="color:#a8a8a8;font-size:12px">{{ $u->name }}</div>
                    <div style="color:#a8a8a8;font-size:11px">
                        {{ number_format($u->followers_count) }} abonnés
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    @if($tag)
    <h3 style="font-size:20px;font-weight:700;margin-bottom:16px">#{{ $tag }}</h3>
    @elseif($query)
    <h3 style="font-size:14px;font-weight:700;color:#a8a8a8;margin-bottom:12px;
               text-transform:uppercase;letter-spacing:0.5px">Publications</h3>
    @endif

    <!-- Grille posts -->
    @if($posts->count() > 0)
    <div class="ig-profile-grid" id="explore-grid">
        @foreach($posts as $post)
        <a href="{{ route('posts.show', $post) }}" class="ig-grid-item">
            @if($post->thumbnail())
            <img src="{{ $post->thumbnail()->url() }}" alt=""
                 loading="lazy"
                 style="width:100%;height:100%;object-fit:cover;display:block">
            @else
            <div style="width:100%;height:100%;background:#1e1e2e"></div>
            @endif
            <div class="ig-grid-overlay">
                <div class="ig-grid-stat">
                    <svg width="18" height="18" viewBox="0 0 32 32" fill="white">
                        <path d="M16 28S4 20 4 12a8 8 0 0 1 12-6.928A8 8 0 0 1 28 12c0 8-12 16-12 16Z"/>
                    </svg>
                    {{ number_format($post->likes_count) }}
                </div>
                <div class="ig-grid-stat">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="white">
                        <path d="M20.656 17.008a9.993 9.993 0 1 0-3.59 3.615L22 22Z"/>
                    </svg>
                    {{ number_format($post->comments_count) }}
                </div>
            </div>
        </a>
        @endforeach
    </div>

    @else
    @if($query || $tag)
    <div style="text-align:center;padding:60px;color:#a8a8a8">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none"
             stroke="#a8a8a8" stroke-width="1" style="margin:0 auto 16px;display:block">
            <path d="M19 10.5A8.5 8.5 0 1 1 10.5 2a8.5 8.5 0 0 1 8.5 8.5Z"/>
            <line x1="16.511" y1="16.511" x2="22" y2="22"/>
        </svg>
        <h3 style="color:#fff;font-size:18px;font-weight:700;margin-bottom:8px">
            Aucun résultat
        </h3>
        <p style="font-size:14px">Essayez avec d'autres mots-clés.</p>
    </div>
    @endif
    @endif

    <!-- ── Infinite scroll trigger ── -->
    @if(method_exists($posts,'hasMorePages') && $posts->hasMorePages())
    <div id="explore-trigger"
         style="height:60px;display:flex;align-items:center;justify-content:center;
                margin-top:8px">
        <div id="explore-loader" style="display:none;align-items:center;justify-content:center">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                 stroke="#a8a8a8" stroke-width="2"
                 style="animation:spin 1s linear infinite">
                <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
            </svg>
        </div>
    </div>

    <script>
    (function() {
        let page    = {{ $posts->currentPage() }};
        let loading = false;
        let hasMore = true;

        const trigger = document.getElementById('explore-trigger');
        const loader  = document.getElementById('explore-loader');
        const grid    = document.getElementById('explore-grid');

        if (!trigger || !grid) return;

        const observer = new IntersectionObserver(async (entries) => {
            if (!entries[0].isIntersecting || loading || !hasMore) return;
            loading = true;
            loader.style.display = 'flex';
            try {
                page++;
                const params = new URLSearchParams(window.location.search);
                params.set('page', page);
                const res  = await fetch('/explore?' + params.toString(), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const html = await res.text();
                const doc  = new DOMParser().parseFromString(html, 'text/html');
                const items = doc.querySelectorAll('#explore-grid a');
                if (!items.length) {
                    hasMore = false;
                    trigger.remove();
                    return;
                }
                items.forEach(item => grid.appendChild(item.cloneNode(true)));
                if (!doc.getElementById('explore-trigger')) hasMore = false;
            } catch(e) {
                console.error('Explore scroll error:', e);
            } finally {
                loading = false;
                loader.style.display = 'none';
            }
        }, { threshold: 0.1 });

        observer.observe(trigger);
    })();
    </script>
    @endif
</div>

<style>
@keyframes spin { to { transform: rotate(360deg); } }
</style>
</x-app-layout>
