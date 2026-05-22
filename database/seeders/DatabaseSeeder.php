<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Follow;
use App\Models\Like;
use App\Models\Post;
use App\Models\PostMedia;
use App\Models\Profile;
use App\Models\Story;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    // Images publiques libres de droits (picsum.photos = aléatoire mais stable par seed)
    private function avatarUrl(int $seed): string
    {
        return "https://picsum.photos/seed/avatar{$seed}/150/150";
    }

    private function postImageUrl(int $seed): string
    {
        return "https://picsum.photos/seed/post{$seed}/600/600";
    }

    public function run(): void
    {
        // ── 10 utilisateurs ──────────────────────────────────────────
        $usersData = [
            ['username' => 'testuser',    'name' => 'Test User',      'bio' => '📸 Photographe amateur | 🌍 Voyageur', 'website' => 'https://example.com'],
            ['username' => 'alice',       'name' => 'Alice Martin',   'bio' => '🌸 Art & Design | Paris 🗼',           'website' => 'https://alice.dev'],
            ['username' => 'bob',         'name' => 'Bob Dupont',     'bio' => '🎵 Musicien | 🎸 Rock forever',        'website' => null],
            ['username' => 'carol',       'name' => 'Carol Petit',    'bio' => '🍕 Food lover | Chef amateur 👩‍🍳',    'website' => 'https://carol.food'],
            ['username' => 'dave',        'name' => 'Dave Lambert',   'bio' => '💪 Fitness | 🏋️ Coach sportif',       'website' => null],
            ['username' => 'eva',         'name' => 'Eva Rousseau',   'bio' => '📚 Auteure | ✍️ Passion écriture',    'website' => 'https://eva.writes'],
            ['username' => 'felix',       'name' => 'Félix Moreau',   'bio' => '🚀 Dev | 💻 Open source enthusiast',  'website' => 'https://felix.dev'],
            ['username' => 'grace',       'name' => 'Grace Leroy',    'bio' => '🎨 Illustratrice | 🖌️ Aquarelle',    'website' => null],
            ['username' => 'hugo',        'name' => 'Hugo Bernard',   'bio' => '🌿 Nature | 📷 Landscape photography','website' => null],
            ['username' => 'iris',        'name' => 'Iris Fontaine',  'bio' => '💃 Danseuse | 🎭 Théâtre',            'website' => 'https://iris.dance'],
        ];

        $users = [];
        foreach ($usersData as $i => $data) {
            $user = User::create([
                'name'     => $data['name'],
                'username' => $data['username'],
                'email'    => $data['username'] . '@example.com',
                'password' => Hash::make('password'),
            ]);

            Profile::create([
                'user_id'   => $user->id,
                'full_name' => $data['name'],
                'bio'       => $data['bio'],
                'website'   => $data['website'],
            ]);

            $users[] = $user;
        }

        // ── Follows réalistes (chaque user suit 4-6 autres) ──────────
        $followGraph = [
            0 => [1, 2, 3, 4, 5],      // testuser suit alice, bob, carol, dave, eva
            1 => [0, 2, 6, 7, 8],      // alice suit testuser, bob, felix, grace, hugo
            2 => [0, 1, 3, 9],         // bob suit testuser, alice, carol, iris
            3 => [0, 1, 4, 5, 7],      // carol suit testuser, alice, dave, eva, grace
            4 => [0, 2, 5, 6],         // dave suit testuser, bob, eva, felix
            5 => [1, 3, 7, 8, 9],      // eva suit alice, carol, grace, hugo, iris
            6 => [0, 4, 8, 9],         // felix suit testuser, dave, hugo, iris
            7 => [1, 3, 5, 9],         // grace suit alice, carol, eva, iris
            8 => [0, 6, 7, 9],         // hugo suit testuser, felix, grace, iris
            9 => [0, 1, 5, 7, 8],      // iris suit testuser, alice, eva, grace, hugo
        ];

        foreach ($followGraph as $followerIdx => $followingIdxs) {
            foreach ($followingIdxs as $followingIdx) {
                Follow::firstOrCreate([
                    'follower_id'  => $users[$followerIdx]->id,
                    'following_id' => $users[$followingIdx]->id,
                ]);
            }
        }

        // ── Posts avec vraies images (Picsum) ─────────────────────────
        $postsData = [
            // [user_idx, caption, image_seed]
            [1, "Golden hour à Paris ✨ #photographie #paris #travel", 10],
            [1, "Mon setup créatif du moment 🎨 #design #workspace", 11],
            [2, "Session studio du soir 🎸 #music #rock #studio", 20],
            [2, "Concert hier soir, quelle énergie ! 🎶 #live #concert", 21],
            [3, "Tarte aux fraises maison 🍓 #food #homemade #cooking", 30],
            [3, "Brunch du dimanche avec les amis ☕ #brunch #friends", 31],
            [3, "Ma dernière création culinaire 🍝 #pasta #homecooking", 32],
            [4, "Morning workout done 💪 #fitness #gym #motivation", 40],
            [4, "Progression du mois — fier du résultat 🏋️ #fitness #progress", 41],
            [5, "Extrait de mon nouveau roman ✍️ #writing #book #literature", 50],
            [6, "Nouveau projet open source lancé 🚀 #dev #opensource #code", 60],
            [6, "Mon terminal setup 🖥️ #terminal #linux #nixos", 61],
            [7, "Aquarelle du soir 🌊 #art #watercolor #illustration", 70],
            [7, "Nouvelle série de portraits 🎨 #portrait #art #drawing", 71],
            [8, "Lever de soleil en montagne 🏔️ #nature #landscape #photography", 80],
            [8, "Forêt enchantée après la pluie 🌿 #nature #forest #rain", 81],
            [8, "Coucher de soleil sur le lac 🌅 #sunset #lake #nature", 82],
            [9, "Répétition du spectacle 💃 #dance #theatre #passion", 90],
            [0, "Premier post ! Ravi d'être ici 🙏 #nouveaucompte", 100],
        ];

        $createdPosts = [];
        foreach ($postsData as [$userIdx, $caption, $imgSeed]) {
            $post = Post::create([
                'user_id'          => $users[$userIdx]->id,
                'caption'          => $caption,
                'comments_enabled' => true,
            ]);

            // Stocke l'URL externe directement comme path
            // (on utilise une URL externe — pas de téléchargement)
            PostMedia::create([
                'post_id' => $post->id,
                'path'    => "https://picsum.photos/seed/post{$imgSeed}/600/600",
                'type'    => 'image',
                'order'   => 0,
            ]);

            $createdPosts[] = ['post' => $post, 'user_idx' => $userIdx];
        }

        // ── Likes (utilisateurs qui likent des posts d'autres) ────────
        $likeMatrix = [
            // post_idx => [user_idxs qui likent]
            0  => [0, 2, 4, 6, 8],
            1  => [0, 3, 5, 9],
            2  => [1, 3, 5, 7],
            3  => [0, 1, 4, 6],
            4  => [0, 2, 6, 8, 9],
            5  => [0, 1, 2, 7],
            6  => [2, 4, 8],
            7  => [1, 3, 5, 9],
            8  => [0, 2, 6],
            9  => [1, 4, 7, 8],
            10 => [0, 3, 5, 9],
            11 => [2, 4, 7],
            12 => [1, 3, 6, 9],
            13 => [0, 2, 5, 8],
            14 => [1, 3, 7, 9],
            15 => [0, 4, 6, 8],
            16 => [2, 5, 7, 9],
            17 => [1, 3, 6],
            18 => [1, 2, 3, 4, 5],
        ];

        foreach ($likeMatrix as $postIdx => $likerIdxs) {
            if (!isset($createdPosts[$postIdx])) continue;
            $post = $createdPosts[$postIdx]['post'];
            foreach ($likerIdxs as $likerIdx) {
                Like::firstOrCreate([
                    'user_id'       => $users[$likerIdx]->id,
                    'likeable_id'   => $post->id,
                    'likeable_type' => Post::class,
                ]);
                $post->increment('likes_count');
            }
        }

        // ── Commentaires ──────────────────────────────────────────────
        $commentsData = [
            [0, 0, "Magnifique photo ! 😍"],
            [2, 0, "Paris me manque tellement 🗼"],
            [4, 1, "Super setup ! Tu utilises quoi comme logiciel ?"],
            [0, 2, "J'adore ce son 🎸🔥"],
            [1, 3, "J'aurais adoré être là !"],
            [0, 4, "Ça donne faim 😋"],
            [2, 4, "La recette s'il te plaît 🙏"],
            [6, 5, "Ça l'air délicieux !"],
            [0, 7, "Inspirant comme toujours 💪"],
            [3, 8, "Belle progression ! Continue ! 🏋️"],
            [1, 12, "Trop beau ce dessin ! 🎨"],
            [5, 13, "Tu as un talent fou 👏"],
            [0, 14, "La nature c'est magique 🏔️"],
            [6, 15, "Quelle lumière incroyable 🌅"],
            [1, 18, "Bienvenue ! 🎉"],
            [2, 18, "Content de te voir ici 👋"],
            [3, 18, "Bienvenue dans la communauté ! 🙌"],
        ];

        foreach ($commentsData as [$commenterIdx, $postIdx, $body]) {
            if (!isset($createdPosts[$postIdx])) continue;
            $post = $createdPosts[$postIdx]['post'];
            Comment::create([
                'user_id' => $users[$commenterIdx]->id,
                'post_id' => $post->id,
                'body'    => $body,
            ]);
            $post->increment('comments_count');
        }

        // ── Stories actives ───────────────────────────────────────────
        foreach ([1, 2, 3, 7, 8] as $userIdx) {
            Story::create([
                'user_id'    => $users[$userIdx]->id,
                'media_path' => "https://picsum.photos/seed/story{$userIdx}/400/700",
                'type'       => 'image',
                'expires_at' => now()->addHours(20),
            ]);
        }
    }
}
