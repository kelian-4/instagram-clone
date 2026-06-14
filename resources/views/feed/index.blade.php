<x-app-layout>
    <div style="display: flex; justify-content: center; padding: 0 0 60px;">

        <!-- ── Colonne centrale (Feed) ── -->
        <div style="width: 100%; max-width: 470px;">

            <!-- ════ STORIES ════ -->
            <div style="padding: 16px 0 12px; border-bottom: 1px solid #262626; margin-bottom: 4px;">
                <div style="display: flex; gap: 4px; overflow-x: auto; padding: 0 12px 4px;">

                    <!-- Formulaire d'ajout de Story -->
                    <form action="{{ route('stories.store') }}" method="POST" enctype="multipart/form-data" id="story-form" style="display:none">
                        @csrf
                        <input type="file" id="story-file" name="media" accept="image/*,video/*" onchange="document.getElementById('story-form').submit()">
                    </form>

                    <!-- Vignette "Ajouter une story" -->
                    <div onclick="document.getElementById('story-file').click()" style="display: flex; flex-direction: column; align-items: center; gap: 5px; cursor: pointer; flex-shrink: 0; width: 72px;">
                        <div style="position: relative; width: 66px; height: 66px;">
                            @if(auth()->user()->hasActiveStory())
                                <div style="width: 66px; height: 66px; border-radius: 50%; padding: 2px; background: linear-gradient(45deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888)">
                                    <img src="{{ auth()->user()->avatarUrl() }}" alt="" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 2px solid #000; display: block;">
                                </div>
                            @else
                                <img src="{{ auth()->user()->avatarUrl() }}" alt="" style="width: 62px; height: 62px; border-radius: 50%; object-fit: cover; border: 2px solid #262626; display: block; margin: 2px;">
                            @endif
                            <div style="position: absolute; bottom: 1px; right: 1px; width: 22px; height: 22px; border-radius: 50%; background: #0095f6; border: 2px solid #000; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 300; color: #fff; line-height: 1;">+</div>
                        </div>
                        <span style="font-size: 12px; color: #f5f5f5; width: 72px; text-align: center; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; padding: 0 4px;">Votre story</span>
                    </div>

                    <!-- Liste des Stories des abonnements -->
                    @foreach($stories as $userId => $userStories)
                        @php
                            $firstStory = $userStories->first();
                            $storyUser = $firstStory->user; // Nettoyé du caractère invisible \xa0
                            $seen = $userStories->every(fn($s) => $s->views->contains('user_id', auth()->id()));
                        @endphp
                        <div onclick="Livewire.dispatch('openStory', { userId: {{ $userId }} })" style="display: flex; flex-direction: column; align-items: center; gap: 5px; cursor: pointer; flex-shrink: 0; width: 72px;">
                            <div style="width: 66px; height: 66px; border-radius: 50%; padding: 2px; background: {{ $seen ? '#333' : 'linear-gradient(45deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888)' }}">
                                <img src="{{ $storyUser->avatarUrl() }}" alt="{{ $storyUser->username }}" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 2px solid #000; display: block;">
                            </div>
                            <span style="font-size: 12px; color: #f5f5f5; width: 72px; text-align: center; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; padding: 0 4px;">{{ $storyUser->username }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- ════ LISTE DES POSTS ════ -->
            @forelse($posts as $post)
                <article style="border-bottom: 1px solid #262626; margin-bottom: 16px;">

                    <!-- Post Header -->
                    <div style="display: flex; align-items: center; padding: 10px 12px; gap: 10px;">
                        <a href="{{ route('profile.show', $post->user->username) }}" style="flex-shrink: 0;">
                            @if($post->user->hasActiveStory())
                                <div style="width: 38px; height: 38px; border-radius: 50%; padding: 2px; background: linear-gradient(45deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888)">
                                    <img src="{{ $post->user->avatarUrl() }}" alt="" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 2px solid #000; display: block;">
                                </div>
                            @else
                                <img src="{{ $post->user->avatarUrl() }}" alt="" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 1px solid #262626;">
                            @endif
                        </a>

                        <div style="flex: 1; min-width: 0;">
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <a href="{{ route('profile.show', $post->user->username) }}" style="font-weight: 700; font-size: 14px; color: #fff; text-decoration: none;">
                                    {{ $post->user->username }}
                                </a>
                                <span style="color: #a8a8a8; font-size: 13px;">•</span>
                                <span style="color: #a8a8a8; font-size: 13px; white-space: nowrap;">
                                    {{ $post->created_at->diffForHumans(null, true) }}
                                </span>
                            </div>
                            @if($post->location)
                                <div style="font-size: 12px; color: #a8a8a8;">{{ $post->location }}</div>
                            @endif
                        </div>

                        <!-- Menu Options du Post -->
                        <div x-data="{ m: false }" style="position: relative;">
                            <button @click="m = !m" style="background: none; border: none; cursor: pointer; padding: 4px;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="white">
                                    <circle cx="12" cy="5" r="1.5"/>
                                    <circle cx="12" cy="12" r="1.5"/>
                                    <circle cx="12" cy="19" r="1.5"/>
                                </svg>
                            </button>
                            <div x-show="m" x-cloak @click.outside="m = false" style="position: absolute; right: 0; top: 32px; background: #262626; border-radius: 12px; padding: 8px 0; min-width: 200px; box-shadow: 0 4px 20px rgba(0,0,0,0.8); z-index: 50;">
                                @if(auth()->id() === $post->user_id)
                                    <form action="{{ route('posts.destroy', $post) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="width: 100%; padding: 12px 16px; text-align: left; font-size: 14px; color: #ff3040; font-weight: 600; background: none; border: none; cursor: pointer;">
                                            Supprimer
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('posts.show', $post) }}" style="display: block; padding: 12px 16px; font-size: 14px; color: #fff; text-decoration: none;">
                                    Voir le post
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Zone Média (Image / Vidéo / Carrousel) -->
                    @if($post->media->count() > 1)
                        <div x-data="{ cur: 0, tot: {{ $post->media->count() }} }" style="position: relative; overflow: hidden; background: #000; aspect-ratio: 1;">
                            <div style="display: flex; height: 100%; transition: transform .3s ease;" :style="'transform:translateX(-' + (cur*100) + '%)'">
                                @foreach($post->media as $m)
                                    <div style="min-width: 100%; height: 100%;">
                                        @if($m->type === 'video')
                                            <video src="{{ $m->url() }}" autoplay muted loop playsinline style="width: 100%; height: 100%; object-fit: cover;"></video>
                                        @else
                                            <img src="{{ $m->url() }}" alt="" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <button x-show="cur > 0" @click="cur--" style="position: absolute; left: 8px; top: 50%; transform: translateY(-50%); width: 28px; height: 28px; border-radius: 50%; background: rgba(255,255,255,0.9); border: none; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="3"><polyline points="15 18 9 12 15 6"/></svg>
                            </button>
                            <button x-show="cur < tot - 1" @click="cur++" style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); width: 28px; height: 28px; border-radius: 50%; background: rgba(255,255,255,0.9); border: none; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="3"><polyline points="9 18 15 12 9 6"/></svg>
                            </button>
                            <div style="position: absolute; bottom: 10px; left: 50%; transform: translateX(-50%); display: flex; gap: 4px;">
                                <template x-for="i in tot" :key="i">
                                    <div :style="'width:6px;height:6px;border-radius:50%;transition:background .2s;background:' + (cur===i-1 ? '#fff' : 'rgba(255,255,255,0.4)')"></div>
                                </template>
                            </div>
                            <div style="position: absolute; top: 10px; right: 12px; background: rgba(0,0,0,0.6); color: #fff; font-size: 12px; padding: 2px 8px; border-radius: 10px;">
                                <span x-text="cur + 1"></span>/{{ $post->media->count() }}
                            </div>
                        </div>
                    @elseif($post->media->count() === 1)
                        @php $m = $post->media->first(); @endphp
                        <div style="background: #000;">
                            @if($m->type === 'video')
                                <video src="{{ $m->url() }}" autoplay muted loop playsinline style="width: 100%; max-height: 585px; object-fit: cover; display: block;"></video>
                            @else
                                <img src="{{ $m->url() }}" alt="" loading="lazy" style="width: 100%; max-height: 585px; object-fit: cover; display: block;">
                            @endif
                        </div>
                    @endif

                    <!-- Zone des Interactions -->
                    <div style="padding: 0 12px;">
                        <div style="display: flex; align-items: center; padding: 6px 0; gap: 2px;">

                            <!-- Bouton J'aime Livewire -->
                            @livewire('like-button', ['post' => $post], key('like-'.$post->id))

                            <!-- Bouton Commentaire -->
                            <a href="{{ route('posts.show', $post) }}" aria-label="Commenter" style="padding: 6px; display: flex; align-items: center; gap: 6px; text-decoration: none; transition: opacity .15s; color: #a8a8a8;" onmouseover="this.style.opacity='.7'" onmouseout="this.style.opacity='1'">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20.656 17.008a9.993 9.993 0 1 0-3.59 3.615L22 22Z"/>
                                </svg>
                                <span style="font-size: 14px; font-weight: 700; color: #fff;">{{ number_format($post->comments_count) }}</span>
                            </a>

                            <!-- Bouton & Popover de Partage DM -->
                            <div x-data="{
                                open: false,
                                search: '',
                                selected: [],
                                sending: false,
                                sent: false,
                                toastMsg: '',
                                showToast: false,
                                toggle(id) {
                                    const i = this.selected.indexOf(id);
                                    if (i === -1) this.selected.push(id);
                                    else this.selected.splice(i, 1);
                                },
                                isSelected(id) { return this.selected.includes(id); },
                                toast(msg) {
                                    this.toastMsg = msg;
                                    this.showToast = true;
                                    setTimeout(() => this.showToast = false, 2500);
                                },
                                copyLink() {
                                    navigator.clipboard.writeText('{{ route('posts.show', $post) }}');
                                    this.toast('Lien copié !');
                                },
                                async send() {
                                    if (!this.selected.length || this.sending) return;
                                    this.sending = true;
                                    try {
                                        for (const uid of this.selected) {
                                            await fetch('/share-post', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                                                },
                                                body: JSON.stringify({ user_id: uid, post_url: '{{ route('posts.show', $post) }}', post_id: {{ $post->id }} })
                                            });
                                        }
                                        this.sent = true;
                                        this.toast('Envoyé !');
                                        setTimeout(() => { this.open = false; this.sent = false; this.selected = []; this.search = ''; }, 1500);
                                    } catch(e) { this.toast('Erreur réseau'); }
                                    finally { this.sending = false; }
                                },
                                async shareToStory() {
                                    try {
                                        const r = await fetch('/share-to-story/{{ $post->id }}', {
                                            method: 'POST',
                                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
                                        });
                                        const d = await r.json();
                                        if (d.ok) { this.toast('Ajouté à votre story !'); setTimeout(() => { this.open = false; }, 1500); }
                                    } catch(e) { this.toast('Erreur'); }
                                },
                                openModal() {
                                    this.open = true;
                                    this.selected = [];
                                    this.search = '';
                                    this.sent = false;
                                    this.$nextTick(() => this.$refs.searchInput?.focus());
                                }
                            }" style="position: relative;">

                                <button @click="openModal()" aria-label="Partager" style="padding: 6px; display: flex; align-items: center; background: none; border: none; cursor: pointer; transition: opacity .15s" onmouseover="this.style.opacity='.7'" onmouseout="this.style.opacity='1'">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="22" y1="2" x2="11" y2="13"/>
                                        <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                                    </svg>
                                </button>

                                <!-- Alerte Toast intégrée -->
                                <div x-show="showToast" x-cloak x-transition style="position: fixed; bottom: 84px; left: 50%; transform: translateX(-50%); background: #323232; color: #fff; padding: 10px 20px; border-radius: 20px; font-size: 14px; font-weight: 600; z-index: 700; white-space: nowrap; box-shadow: 0 4px 16px rgba(0,0,0,0.5);" x-text="toastMsg"></div>

                                <!-- Structure du Popover de Partage réorganisé (Plus de fuite de DOM) -->
                                <div x-show="open" x-cloak @keydown.escape.window="open = false" style="position: fixed; inset: 0; z-index: 500; pointer-events: none;">
                                    <div @click="open = false" style="position: absolute; inset: 0; pointer-events: all;"></div>

                                    <div @click.stop style="position: absolute; bottom: 80px; left: 50%; transform: translateX(-50%); pointer-events: all; background: #1c1c1c; border-radius: 16px; width: 90vw; max-width: 360px; max-height: 60vh; display: flex; flex-direction: column; box-shadow: 0 20px 60px rgba(0,0,0,0.9); overflow: hidden;">

                                        <!-- En-tête Popover -->
                                        <div style="padding: 16px; border-bottom: 1px solid #2a2a2a; flex-shrink: 0;">
                                            <h3 style="text-align: center; font-size: 16px; font-weight: 700; color: #fff; margin: 0 0 12px;">Partager</h3>
                                            <div style="position: relative;">
                                                <svg style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); pointer-events: none;" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#a8a8a8" stroke-width="2">
                                                    <path d="M19 10.5A8.5 8.5 0 1 1 10.5 2a8.5 8.5 0 0 1 8.5 8.5Z"/>
                                                    <line x1="16.511" y1="16.511" x2="22" y2="22"/>
                                                </svg>
                                                <input x-ref="searchInput" x-model="search" type="text" placeholder="Rechercher..." style="width: 100%; background: #2a2a2a; border: none; border-radius: 10px; padding: 10px 12px 10px 38px; color: #fff; font-size: 14px; outline: none; font-family: inherit; box-sizing: border-box;">
                                            </div>
                                        </div>

                                        <!-- Liste des Amis (Abonnements) -->
                                        <div style="flex: 1; overflow-y: auto; padding: 6px 0;">
                                            @forelse(auth()->user()->following()->get() as $friend)
                                                <div x-show="search === '' || '{{ strtolower($friend->username . ' ' . $friend->name) }}'.includes(search.toLowerCase())" @click="toggle({{ $friend->id }})" :style="isSelected({{ $friend->id }}) ? 'background:#252525' : ''" style="display: flex; align-items: center; gap: 12px; padding: 10px 16px; cursor: pointer; transition: background .1s;">
                                                    <img src="{{ $friend->avatarUrl() }}" alt="{{ $friend->username }}" style="width: 44px; height: 44px; border-radius: 50%; object-fit: cover; flex-shrink: 0;">
                                                    <div style="flex: 1; min-width: 0;">
                                                        <div style="font-weight: 700; font-size: 14px; color: #fff; margin-bottom: 2px;">{{ $friend->username }}</div>
                                                        <div style="color: #a8a8a8; font-size: 13px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $friend->name }}</div>
                                                    </div>
                                                    <div :style="isSelected({{ $friend->id }}) ? 'background:#0095f6;border-color:#0095f6' : 'background:transparent;border-color:#555'" style="width: 22px; height: 22px; border-radius: 50%; border: 2px solid #555; flex-shrink: 0; display: flex; align-items: center; justify-content: center; transition: all .15s;">
                                                        <svg x-show="isSelected({{ $friend->id }})" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                            <polyline points="20 6 9 17 4 12"/>
                                                        </svg>
                                                    </div>
                                                </div>
                                            @empty
                                                <div style="text-align: center; padding: 24px; color: #a8a8a8; font-size: 14px;">Suivez des personnes pour partager.</div>
                                            @endforelse
                                        </div>

                                        <!-- Actions du Bas -->
                                        <div style="padding: 12px; border-top: 1px solid #2a2a2a; flex-shrink: 0; display: flex; flex-direction: column; gap: 8px;">
                                            <button @click="shareToStory()" style="width: 100%; padding: 10px; background: #2a2a2a; border: none; border-radius: 10px; color: #fff; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                                                Ajouter à ma story
                                            </button>
                                            <button @click="copyLink()" style="width: 100%; padding: 10px; background: #2a2a2a; border: none; border-radius: 10px; color: #fff; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                                                Copier le lien
                                            </button>
                                            <button @click="send()" :disabled="selected.length === 0 || sending" :style="selected.length > 0 && !sending ? 'opacity:1;cursor:pointer' : 'opacity:0.4;cursor:default'" style="width: 100%; padding: 11px; border: none; border-radius: 10px; background: #0095f6; color: #fff; font-size: 14px; font-weight: 700; transition: opacity .2s;">
                                                <span x-show="!sent && !sending">Envoyer <span x-show="selected.length > 0">(<span x-text="selected.length"></span>)</span></span>
                                                <span x-show="sending && !sent">Envoi...</span>
                                                <span x-show="sent">✓ Envoyé !</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- Fin x-data partage -->

                            <!-- Bouton Repost -->
                            <div x-data="{
                                reposted: false,
                                loading: false,
                                async toggle() {
                                    if (this.loading) return;
                                    this.loading = true;
                                    try {
                                        const r = await fetch('/repost/{{ $post->id }}', {
                                            method: 'POST',
                                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
                                        });
                                        const d = await r.json();
                                        if (d.ok) {
                                            this.reposted = !this.reposted;
                                            this.$refs.icon.animate([
                                                { transform: 'scale(1) rotate(0deg)' },
                                                { transform: 'scale(1.3) rotate(180deg)' },
                                                { transform: 'scale(1) rotate(360deg)' }
                                            ], { duration: 380, easing: 'cubic-bezier(.17,.67,.35,1.2)' });
                                        }
                                    } catch(e) {}
                                    finally { this.loading = false; }
                                }
                            }">
                                <button @click="toggle()" aria-label="Republier" :disabled="loading" style="padding: 6px; display: flex; align-items: center; background: none; border: none; cursor: pointer; transition: opacity .15s" onmouseover="this.style.opacity='.7'" onmouseout="this.style.opacity='1'">
                                    <svg x-ref="icon" width="22" height="22" viewBox="0 0 24 24" fill="none" :stroke="reposted ? '#00ba7c' : 'white'" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="transition: stroke .2s">
                                        <polyline points="17 1 21 5 17 9"/>
                                        <path d="M3 11V9a4 4 0 0 1 4-4h14"/>
                                        <polyline points="7 23 3 19 7 15"/>
                                        <path d="M21 13v2a4 4 0 0 1-4 4H3"/>
                                    </svg>
                                </button>
                            </div>

                            <div style="flex: 1;"></div>

                            <!-- Bouton Signet / Sauvegarde Livewire -->
                            @livewire('bookmark-button', ['post' => $post], key('bm-'.$post->id))
                        </div>

                        <!-- Légende (Caption) -->
                        @if($post->caption)
                            <div style="font-size: 14px; line-height: 1.5; margin-bottom: 4px;">
                                <a href="{{ route('profile.show', $post->user->username) }}" style="font-weight: 700; margin-right: 4px; color: #fff; text-decoration: none;">
                                    {{ $post->user->username }}
                                </a>{!! $post->parsedCaption() !!}
                            </div>
                        @endif

                        <!-- Lien d'accès aux commentaires -->
                        @if($post->comments_count > 0)
                            <a href="{{ route('posts.show', $post) }}" style="color: #a8a8a8; font-size: 14px; display: block; margin-bottom: 6px; text-decoration: none;">
                                Voir les {{ $post->comments_count }} commentaires
                            </a>
                        @endif

                        <!-- Formulaire de Saisie Commentaire Rapide -->
                        @livewire('comment-section', ['post' => $post], key('cs-'.$post->id))

                        <!-- Date de Publication -->
                        <div style="color: #a8a8a8; font-size: 10px; text-transform: uppercase; letter-spacing: 0.3px; margin-top: 8px; padding-bottom: 12px;">
                            {{ $post->created_at->format('d F Y') }}
                        </div>
                    </div>
                </article>
            @empty
                <!-- Suggestions si le fil d'actualité est vide -->
                <div style="padding: 40px 12px 0;">
                    <h3 style="font-size: 16px; font-weight: 600; color: #a8a8a8; margin-bottom: 20px;">Suggestions pour vous</h3>
                    @foreach(\App\Models\User::where('id', '!=', auth()->id())->limit(5)->get() as $s)
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                            <a href="{{ route('profile.show', $s->username) }}">
                                <img src="{{ $s->avatarUrl() }}" alt="" style="width: 44px; height: 44px; border-radius: 50%; object-fit: cover;">
                            </a>
                            <div style="flex: 1;">
                                <a href="{{ route('profile.show', $s->username) }}" style="font-weight: 600; font-size: 14px; color: #fff; display: block; text-decoration: none;">
                                    {{ $s->username }}
                                </a>
                                <span style="color: #a8a8a8; font-size: 12px;">Suggéré pour vous</span>
                            </div>
                            @livewire('follow-button', ['target' => $s], key('sug-'.$s->id))
                        </div>
                    @endforeach
                </div>
            @endforelse

            <!-- Trigger pour l'Infinite Scroll (Intersection Observer) -->
            @if($posts->hasMorePages())
                <div id="infinite-scroll-trigger" style="height: 60px; display: flex; align-items: center; justify-content: center;">
                    <div id="scroll-loader" style="display: none; align-items: center; justify-content: center;">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#a8a8a8" stroke-width="2" style="animation: spin 1s linear infinite;">
                            <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                        </svg>
                    </div>
                </div>

                <script>
                    (function() {
                        let page = {{ $posts->currentPage() }};
                        let loading = false;
                        let hasMore = true;
                        const trigger = document.getElementById('infinite-scroll-trigger');
                        const loader = document.getElementById('scroll-loader');

                        const observer = new IntersectionObserver(async (entries) => {
                            if (!entries[0].isIntersecting || loading || !hasMore) return;
                            loading = true;
                            loader.style.display = 'flex';
                            try {
                                page++;
                                const res = await fetch(`/feed?page=${page}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                                const html = await res.text();
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(html, 'text/html');
                                const posts = doc.querySelectorAll('article');
                                if (posts.length === 0) { hasMore = false; trigger.remove(); return; }
                                posts.forEach(post => trigger.insertAdjacentElement('beforebegin', post));
                                if (!doc.getElementById('infinite-scroll-trigger')) hasMore = false;
                            } catch(e) { console.error(e); }
                            finally { loading = false; loader.style.display = 'none'; }
                        }, { threshold: 0.1 });

                        observer.observe(trigger);
                    })();
                </script>
            @endif
        </div> <!-- Fin colonne centrale -->

        <!-- ── Colonne Droite (Desktop uniquement) ── -->
        <div class="ig-right-col" style="display: none; width: 320px; padding: 24px 0 0 48px; flex-shrink: 0; position: sticky; top: 0; align-self: flex-start;">

            <!-- Profil Utilisateur connecté -->
            <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 20px;">
                <a href="{{ route('profile.show', auth()->user()->username) }}">
                    @if(auth()->user()->hasActiveStory())
                        <div style="width: 58px; height: 58px; border-radius: 50%; padding: 2px; background: linear-gradient(45deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888)">
                            <img src="{{ auth()->user()->avatarUrl() }}" alt="" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 2px solid #000; display: block;">
                        </div>
                    @else
                        <img src="{{ auth()->user()->avatarUrl() }}" alt="" style="width: 56px; height: 56px; border-radius: 50%; object-fit: cover;">
                    @endif
                </a>
                <div style="flex: 1; min-width: 0;">
                    <a href="{{ route('profile.show', auth()->user()->username) }}" style="font-weight: 700; font-size: 14px; color: #fff; display: block; text-decoration: none;">
                        {{ auth()->user()->username }}
                    </a>
                    <span style="color: #a8a8a8; font-size: 14px;">{{ auth()->user()->name }}</span>
                </div>
                <a href="{{ route('profile.edit') }}" style="color: #0095f6; font-size: 12px; font-weight: 600; text-decoration: none;">Modifier</a>
            </div>

            <!-- Liste des suggestions -->
            @if($suggestions->count() > 0)
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                    <span style="font-size: 14px; font-weight: 600; color: #a8a8a8;">Suggestions pour vous</span>
                    <a href="{{ route('explore') }}" style="font-size: 12px; font-weight: 600; color: #fff; text-decoration: none;">Tout voir</a>
                </div>

                @foreach($suggestions as $s)
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                        <a href="{{ route('profile.show', $s->username) }}">
                            <img src="{{ $s->avatarUrl() }}" alt="{{ $s->username }}" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover;">
                        </a>
                        <div style="flex: 1; min-width: 0;">
                            <a href="{{ route('profile.show', $s->username) }}" style="font-weight: 600; font-size: 13px; color: #fff; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; text-decoration: none;">
                                {{ $s->username }}
                            </a>
                            <span style="color: #a8a8a8; font-size: 12px;">Suggéré pour vous</span>
                        </div>
                        @livewire('follow-button', ['target' => $s], key('r-'.$s->id))
                    </div>
                @endforeach
            @endif

            <!-- Pied de page -->
            <div style="margin-top: 20px;">
                <p style="color: #a8a8a8; font-size: 11px; line-height: 2;">
                    À propos · Aide · Confidentialité · Conditions · Emplacements · Langue<br>
                    © 2026 INSTAGRAM FROM META
                </p>
            </div>
        </div> <!-- Fin colonne droite -->

    </div> <!-- Fin du conteneur centré principal -->

    <!-- ── Bouton flottant Messages (Indépendant du Flex global) ── -->
    <a href="{{ route('messages.index') }}" style="position: fixed; bottom: 24px; right: 24px; background: #fff; color: #000; border-radius: 24px; padding: 12px 20px; display: flex; align-items: center; gap: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.4); font-weight: 600; font-size: 15px; text-decoration: none; transition: transform .15s; z-index: 200;" onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='scale(1)'">
        @php
            try {
                $floatUnread = auth()->user()->conversations()
                    ->with(['messages' => fn($q) => $q->where('user_id', '!=', auth()->id())->whereNull('read_at')])
                    ->get()->sum(fn($c) => $c->messages->count());
            } catch(\Exception $e) { $floatUnread = 0; }
        @endphp
        <div style="position: relative;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12.003 2.001a9.705 9.705 0 1 1 0 19.41 9.705 9.705 0 0 1 0-19.41Z"/>
                <path d="M2.84 9.508 21.158 2.84 14.49 21.161l-3.476-7.674Z"/>
            </svg>
            @if($floatUnread > 0)
                <span style="position: absolute; top: -6px; right: -6px; background: #ff3040; color: #fff; font-size: 10px; font-weight: 700; min-width: 16px; height: 16px; border-radius: 8px; display: flex; align-items: center; justify-content: center; padding: 0 3px; border: 2px solid #fff;">{{ $floatUnread }}</span>
            @endif
        </div>
        Messages
    </a>

    <!-- Styles CSS Globaux pour la vue -->
    <style>
        @media (min-width: 1024px) { .ig-right-col { display: block !important; } }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</x-app-layout>
