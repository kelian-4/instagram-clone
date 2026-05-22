<x-app-layout>
<div style="max-width:935px;margin:0 auto;padding:44px 20px 60px">

    <!-- ══ HEADER ══ -->
    <div style="display:flex;gap:80px;margin-bottom:32px;align-items:flex-start;padding:0 20px">

        <!-- Avatar 150px -->
        <div style="flex-shrink:0">
            @if($user->hasActiveStory())
            <div style="width:150px;height:150px;border-radius:50%;padding:3px;
                        background:linear-gradient(45deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);
                        cursor:pointer"
                 onclick="Livewire.dispatch('openStory',{userId:{{$user->id}}})">
                <img src="{{ $user->avatarUrl() }}" alt="{{ $user->username }}"
                     style="width:100%;height:100%;border-radius:50%;object-fit:cover;
                            border:3px solid #0a0a0f;display:block">
            </div>
            @else
            <img src="{{ $user->avatarUrl() }}" alt="{{ $user->username }}"
                 style="width:150px;height:150px;border-radius:50%;object-fit:cover;
                        border:1px solid #333;display:block">
            @endif
        </div>

        <!-- Infos droite -->
        <div style="flex:1;min-width:0">

            <!-- Username + badge vérifié -->
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;flex-wrap:wrap">
                <h1 style="font-size:20px;font-weight:300;color:#fff;margin:0;letter-spacing:-0.3px">
                    {{ $user->username }}
                </h1>
                @if(isset($user->verified) && $user->verified)
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="12" r="10" fill="#0095f6"/>
                    <path d="M9 12l2 2 4-4" stroke="white" stroke-width="2"
                          stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                @endif

                <!-- Boutons owner -->
                @if($isOwner)
                <!-- Pas de boutons sur cette ligne — ils sont sous les stats -->
                @else
                @livewire('follow-button',['target'=>$user],key('fp-'.$user->id))
                @if($isFollowing)
                <a href="{{ route('messages.show',$user) }}"
                   style="padding:7px 16px;background:#1e1e2e;border:none;border-radius:8px;
                          font-size:14px;font-weight:600;color:#fff;text-decoration:none">
                    Message
                </a>
                @endif
                <button style="background:none;border:none;cursor:pointer;padding:4px">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                         stroke="white" stroke-width="2" stroke-linecap="round">
                        <circle cx="12" cy="12" r="1"/>
                        <circle cx="19" cy="12" r="1"/>
                        <circle cx="5"  cy="12" r="1"/>
                    </svg>
                </button>
                @endif
            </div>

            <!-- Stats : publications · followers · suivi(e)s -->
            <div style="display:flex;gap:36px;margin-bottom:16px">
                <div>
                    <span style="font-weight:700;font-size:16px;color:#fff">{{ number_format($user->posts_count) }}</span>
                    <span style="font-size:15px;color:#fff;margin-left:4px">publication{{ $user->posts_count !== 1 ? 's' : '' }}</span>
                </div>
                <div style="cursor:pointer">
                    <span style="font-weight:700;font-size:16px;color:#fff">
                        {{ $user->followers_count >= 10000
                            ? number_format($user->followers_count/1000,1).' k'
                            : number_format($user->followers_count) }}
                    </span>
                    <span style="font-size:15px;color:#fff;margin-left:4px">followers</span>
                </div>
                <div style="cursor:pointer">
                    <span style="font-weight:700;font-size:16px;color:#fff">{{ number_format($user->following_count) }}</span>
                    <span style="font-size:15px;color:#fff;margin-left:4px">suivi(e)s</span>
                </div>
            </div>

            <!-- Nom complet + bio + lien -->
            <div style="font-size:14px;line-height:1.6;color:#fff">
                @if($user->profile?->full_name)
                <div style="font-weight:600;margin-bottom:2px">{{ $user->profile->full_name }}</div>
                @endif
                @if($user->profile?->bio)
                <div style="white-space:pre-line;color:#fff">{{ $user->profile->bio }}</div>
                @endif
                @if($user->profile?->website)
                <a href="{{ $user->profile->website }}" target="_blank"
                   style="color:#a8d8ea;font-weight:600;display:inline-flex;align-items:center;
                          gap:4px;margin-top:4px;text-decoration:none">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2">
                        <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
                        <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
                    </svg>
                    {{ str_replace(['https://','http://'],'',$user->profile->website) }}
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- ══ BOUTONS MODIFIER / VOIR ARCHIVE — pleine largeur, AVANT les stories ══ -->
    @if($isOwner)
    <div style="display:flex;gap:8px;margin:0 20px 24px;padding:0 0px">
        <a href="{{ route('profile.edit') }}"
           style="flex:1;padding:8px 0;background:#1e1e2e;border-radius:10px;
                  font-size:14px;font-weight:600;color:#fff;text-decoration:none;
                  text-align:center;cursor:pointer;transition:background .15s;display:block"
           onmouseover="this.style.background='#252535'"
           onmouseout="this.style.background='#1e1e2e'">
            Modifier le profil
        </a>
        <a href="#"
           style="flex:1;padding:8px 0;background:#1e1e2e;border-radius:10px;
                  font-size:14px;font-weight:600;color:#fff;text-decoration:none;
                  text-align:center;cursor:pointer;transition:background .15s;display:block"
           onmouseover="this.style.background='#252535'"
           onmouseout="this.style.background='#1e1e2e'">
            Voir l'archive
        </a>
        <button style="padding:8px 12px;background:#1e1e2e;border:none;border-radius:10px;
                       cursor:pointer;display:flex;align-items:center;justify-content:center"
                onmouseover="this.style.background='#252535'"
                onmouseout="this.style.background='#1e1e2e'">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                 stroke="white" stroke-width="2" stroke-linecap="round">
                <circle cx="12" cy="12" r="1"/>
                <circle cx="19" cy="12" r="1"/>
                <circle cx="5"  cy="12" r="1"/>
            </svg>
        </button>
    </div>
    @endif

    <!-- ══ STORIES À LA UNE (highlights) ══ -->
    <div style="padding:0 20px;margin-bottom:8px">
        <div style="display:flex;gap:20px;overflow-x:auto;padding-bottom:4px">

            @php
                $highlights = $user->stories()->active()->latest()->get();
            @endphp

            @foreach($highlights as $hl)
            <div style="display:flex;flex-direction:column;align-items:center;
                        gap:8px;cursor:pointer;flex-shrink:0;width:88px"
                 onclick="Livewire.dispatch('openStory',{userId:{{$user->id}}})">
                <!-- Cercle 77px fond sombre, border gris -->
                <div style="width:77px;height:77px;border-radius:50%;
                            background:#1e1e2e;padding:2px;
                            border:1px solid #3a3a4a;overflow:hidden">
                    <img src="{{ $hl->mediaUrl() }}" alt=""
                         style="width:100%;height:100%;border-radius:50%;
                                object-fit:cover;display:block">
                </div>
                <span style="font-size:12px;color:#fff;max-width:88px;text-align:center;
                             overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                    {{ $hl->created_at->format('d/m') }}
                </span>
            </div>
            @endforeach

            <!-- Bouton Nouveau -->
            @if($isOwner)
            <div onclick="document.getElementById('hl-story-file').click()"
                 style="display:flex;flex-direction:column;align-items:center;
                        gap:8px;cursor:pointer;flex-shrink:0;width:88px">
                <form action="{{ route('stories.store') }}" method="POST"
                      enctype="multipart/form-data" id="hl-story-form" style="display:none">
                    @csrf
                    <input type="file" id="hl-story-file" name="media"
                           accept="image/*,video/*"
                           onchange="document.getElementById('hl-story-form').submit()">
                </form>
                <!-- Cercle avec + -->
                <div style="width:77px;height:77px;border-radius:50%;
                            background:#1e1e2e;border:1px solid #3a3a4a;
                            display:flex;align-items:center;justify-content:center">
                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none"
                         stroke="#fff" stroke-width="1.5" stroke-linecap="round">
                        <line x1="12" y1="5" x2="12" y2="19"/>
                        <line x1="5"  y1="12" x2="19" y2="12"/>
                    </svg>
                </div>
                <span style="font-size:12px;color:#fff;text-align:center">Nouveau</span>
            </div>
            @endif
        </div>
    </div>

    <!-- ══ ONGLETS — exactement comme le screenshot ══ -->
    <!-- 5 onglets : grille | reels | enregistrés | reposts | mentions -->
    <div style="display:flex;border-top:1px solid #1e1e2e;margin:0 0 0">

        <!-- 1. Publications (grille 4 carrés) -->
        <a href="{{ route('profile.show',['username'=>$user->username,'tab'=>'posts']) }}"
           style="flex:1;display:flex;align-items:center;justify-content:center;
                  padding:14px 0;text-decoration:none;
                  border-top:{{ $tab==='posts' ? '1.5px solid #fff' : '1.5px solid transparent' }};
                  margin-top:-1px">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                 stroke="{{ $tab==='posts' ? '#fff' : '#666' }}" stroke-width="1.8">
                <rect x="3"  y="3"  width="7" height="7" rx="1"/>
                <rect x="14" y="3"  width="7" height="7" rx="1"/>
                <rect x="3"  y="14" width="7" height="7" rx="1"/>
                <rect x="14" y="14" width="7" height="7" rx="1"/>
            </svg>
        </a>

        <!-- 2. Reels (rectangle arrondi + triangle) -->
        <a href="{{ route('profile.show',['username'=>$user->username,'tab'=>'reels']) }}"
           style="flex:1;display:flex;align-items:center;justify-content:center;
                  padding:14px 0;text-decoration:none;
                  border-top:{{ $tab==='reels' ? '1.5px solid #fff' : '1.5px solid transparent' }};
                  margin-top:-1px">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                 stroke="{{ $tab==='reels' ? '#fff' : '#666' }}" stroke-width="1.8"
                 stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 12v3.45c0 2.849.698 4.005 1.606 4.944.94.909 2.098 1.608 4.946 1.608h6.896c2.848 0 4.006-.7 4.946-1.608C21.302 19.455 22 18.3 22 15.45V8.552c0-2.849-.698-4.006-1.606-4.945C19.454 2.7 18.296 2 15.448 2H8.552c-2.848 0-4.006.699-4.946 1.607C2.698 4.547 2 5.703 2 8.552Z"/>
                <polygon points="10 8.5 16 12 10 15.5"
                         fill="{{ $tab==='reels' ? '#fff' : '#666' }}" stroke="none"/>
            </svg>
        </a>

        <!-- 3. Enregistrés (bookmark) -->
        <a href="{{ route('profile.show',['username'=>$user->username,'tab'=>'saved']) }}"
           style="flex:1;display:flex;align-items:center;justify-content:center;
                  padding:14px 0;text-decoration:none;
                  border-top:{{ $tab==='saved' ? '1.5px solid #fff' : '1.5px solid transparent' }};
                  margin-top:-1px">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                 stroke="{{ $tab==='saved' ? '#fff' : '#666' }}" stroke-width="1.8"
                 stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>
            </svg>
        </a>

        <!-- 4. Reposts (flèches circulaires) -->
        <a href="{{ route('profile.show',['username'=>$user->username,'tab'=>'reposts']) }}"
           style="flex:1;display:flex;align-items:center;justify-content:center;
                  padding:14px 0;text-decoration:none;
                  border-top:{{ $tab==='reposts' ? '1.5px solid #fff' : '1.5px solid transparent' }};
                  margin-top:-1px">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                 stroke="{{ $tab==='reposts' ? '#fff' : '#666' }}" stroke-width="1.8"
                 stroke-linecap="round" stroke-linejoin="round">
                <polyline points="17 2 21 6 17 10"/>
                <path d="M3 11V9a4 4 0 0 1 4-4h14"/>
                <polyline points="7 22 3 18 7 14"/>
                <path d="M21 13v2a4 4 0 0 1-4 4H3"/>
            </svg>
        </a>

        <!-- 5. Mentions (tag) -->
        <a href="{{ route('profile.show',['username'=>$user->username,'tab'=>'tagged']) }}"
           style="flex:1;display:flex;align-items:center;justify-content:center;
                  padding:14px 0;text-decoration:none;
                  border-top:{{ $tab==='tagged' ? '1.5px solid #fff' : '1.5px solid transparent' }};
                  margin-top:-1px">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                 stroke="{{ $tab==='tagged' ? '#fff' : '#666' }}" stroke-width="1.8"
                 stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                <line x1="7" y1="7" x2="7.01" y2="7"/>
            </svg>
        </a>
    </div>

    <!-- ══ CONTENU GRILLE ══ -->
    @php
        $gridPosts = match($tab) {
            'reels'   => $user->posts()->where('is_reel', true)->with('media')->latest()->get(),
            'saved'   => auth()->id() === $user->id
                ? \App\Models\Post::whereHas('bookmarks', fn($q) => $q->where('user_id', $user->id))
                    ->with(['user','media'])->latest()->get()
                : collect(),
            'reposts' => \App\Models\Post::where('caption', 'like',
                            '%🔁 Republié de @' . $user->username . '%')
                            ->with(['user','media'])->latest()->get(),
            'tagged'  => collect(),
            default   => $user->posts()->where('is_reel', false)->with('media')->latest()->get(),
        };
    @endphp

    @if($gridPosts->count() > 0)
    <!-- Grille 3:4 comme Instagram 2026 -->
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:3px">
        @foreach($gridPosts as $gpost)
        <a href="{{ route('posts.show',$gpost) }}"
           style="position:relative;aspect-ratio:3/4;overflow:hidden;display:block">

            @if($gpost->is_reel)
            <div style="position:absolute;top:8px;right:8px;z-index:5;
                        filter:drop-shadow(0 1px 3px rgba(0,0,0,0.6))">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="white">
                    <path d="M2 12v3.45c0 2.849.698 4.005 1.606 4.944.94.909 2.098 1.608 4.946 1.608h6.896c2.848 0 4.006-.7 4.946-1.608C21.302 19.455 22 18.3 22 15.45V8.552c0-2.849-.698-4.006-1.606-4.945C19.454 2.7 18.296 2 15.448 2H8.552c-2.848 0-4.006.699-4.946 1.607C2.698 4.547 2 5.703 2 8.552Z"/>
                    <polygon points="10 8.5 16 12 10 15.5" fill="white" stroke="none"/>
                </svg>
            </div>
            @elseif($gpost->media->count() > 1)
            <div style="position:absolute;top:8px;right:8px;z-index:5;
                        filter:drop-shadow(0 1px 3px rgba(0,0,0,0.6))">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="white">
                    <path d="M19 15V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2Z"/>
                    <path d="M21 19H7a2 2 0 0 1-2-2V5" stroke="white" fill="none" stroke-width="2"/>
                </svg>
            </div>
            @endif

            @if($gpost->thumbnail())
            <img src="{{ $gpost->thumbnail()->url() }}" alt=""
                 loading="lazy"
                 style="width:100%;height:100%;object-fit:cover;display:block;transition:filter .2s">
            @else
            <div style="width:100%;height:100%;background:#1e1e2e;
                        display:flex;align-items:center;justify-content:center">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                     stroke="#444" stroke-width="1.5">
                    <rect x="3" y="3" width="18" height="18" rx="3"/>
                </svg>
            </div>
            @endif

            <div class="ig-grid-overlay">
                <div class="ig-grid-stat">
                    <svg width="16" height="16" viewBox="0 0 32 32" fill="white">
                        <path d="M16 28S4 20 4 12a8 8 0 0 1 12-6.928A8 8 0 0 1 28 12c0 8-12 16-12 16Z"/>
                    </svg>
                    {{ number_format($gpost->likes_count) }}
                </div>
                <div class="ig-grid-stat">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="white">
                        <path d="M20.656 17.008a9.993 9.993 0 1 0-3.59 3.615L22 22Z"/>
                    </svg>
                    {{ number_format($gpost->comments_count) }}
                </div>
            </div>
        </a>
        @endforeach
    </div>

    @elseif($tab === 'tagged')
    <div style="text-align:center;padding:80px 20px;color:#a8a8a8">
        <svg width="62" height="62" viewBox="0 0 24 24" fill="none"
             stroke="#a8a8a8" stroke-width="1" style="margin:0 auto 16px;display:block">
            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
            <line x1="7" y1="7" x2="7.01" y2="7"/>
        </svg>
        <h3 style="font-size:22px;font-weight:700;color:#fff;margin-bottom:8px">
            Photos et vidéos de vous
        </h3>
        <p style="font-size:14px">
            Quand des personnes vous identifient, elles apparaissent ici.
        </p>
    </div>

    @elseif($tab === 'saved' && auth()->id() !== $user->id)
    <div style="text-align:center;padding:80px 20px;color:#a8a8a8">
        <h3 style="font-size:18px;font-weight:700;color:#fff;margin-bottom:8px">
            Contenu privé
        </h3>
        <p style="font-size:14px">
            Les enregistrements ne sont visibles que par leur propriétaire.
        </p>
    </div>

    @else
    <div style="text-align:center;padding:80px 20px;color:#a8a8a8">
        <svg width="62" height="62" viewBox="0 0 24 24" fill="none"
             stroke="#a8a8a8" stroke-width="1" style="margin:0 auto 20px;display:block">
            <rect x="3" y="3" width="18" height="18" rx="3"/>
            <circle cx="8.5" cy="8.5" r="1.5" fill="#a8a8a8" stroke="none"/>
            <polyline points="21 15 16 10 5 21"/>
        </svg>
        @if($isOwner)
        <h3 style="font-size:22px;font-weight:700;color:#fff;margin-bottom:8px">
            Partagez vos photos
        </h3>
        <p style="font-size:14px;margin-bottom:20px">
            Quand vous partagez des photos, elles s'affichent ici.
        </p>
        <span x-data @click="$dispatch('open-create-post')"
              style="color:#0095f6;font-size:14px;font-weight:700;cursor:pointer">
            Partager votre première photo
        </span>
        @else
        <h3 style="font-size:18px;font-weight:700;color:#fff;margin-bottom:8px">
            Aucune publication
        </h3>
        @endif
    </div>
    @endif

</div>

<!-- Bouton flottant Messages -->
<a href="{{ route('messages.index') }}"
   style="position:fixed;bottom:24px;right:24px;
          background:#fff;color:#000;border-radius:24px;
          padding:11px 18px;display:flex;align-items:center;gap:9px;
          box-shadow:0 4px 20px rgba(0,0,0,0.5);font-weight:700;font-size:14px;
          text-decoration:none;transition:transform .15s;z-index:200"
   onmouseover="this.style.transform='scale(1.03)'"
   onmouseout="this.style.transform='scale(1)'">
    @php
        try {
            $pfUnread = auth()->user()->conversations()
                ->with(['messages'=>fn($q)=>$q->where('user_id','!=',auth()->id())->whereNull('read_at')])
                ->get()->sum(fn($c)=>$c->messages->count());
        } catch(\Exception $e){ $pfUnread=0; }
    @endphp
    <div style="position:relative">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
             stroke="#000" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 2L11 13"/>
            <path d="M22 2L15 22L11 13L2 9L22 2Z"/>
        </svg>
        @if($pfUnread > 0)
        <span style="position:absolute;top:-5px;right:-5px;background:#ff3040;
                     color:#fff;font-size:9px;font-weight:700;min-width:14px;height:14px;
                     border-radius:7px;display:flex;align-items:center;justify-content:center;
                     padding:0 2px;border:2px solid #fff">{{ $pfUnread }}</span>
        @endif
    </div>
    Messages
</a>
</x-app-layout>
