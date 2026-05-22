<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostMedia;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReelSeeder extends Seeder
{
    // Vidéos libres de droits (sample-videos.com + coverr.co)
    private array $videos = [
        [
            'url'     => 'https://www.w3schools.com/html/mov_bbb.mp4',
            'caption' => '🎬 Big Buck Bunny #reel #fun #animation',
            'user'    => 'alice',
        ],
        [
            'url'     => 'https://www.w3schools.com/html/movie.mp4',
            'caption' => '🌊 Ocean vibes #nature #reel #waves',
            'user'    => 'bob',
        ],
        [
            'url'     => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4',
            'caption' => '🔥 Fire 🔥 #reel #viral #trending',
            'user'    => 'carol',
        ],
        [
            'url'     => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4',
            'caption' => '✈️ Travel goals #travel #reel #adventure',
            'user'    => 'dave',
        ],
        [
            'url'     => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerFun.mp4',
            'caption' => '😂 Fun times #fun #reel #humor',
            'user'    => 'eva',
        ],
        [
            'url'     => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4',
            'caption' => '🚗 Road trip #reel #drive #lifestyle',
            'user'    => 'felix',
        ],
        [
            'url'     => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/SubaruOutbackOnStreetAndDirt.mp4',
            'caption' => '🏔️ Off-road adventures #reel #outdoor',
            'user'    => 'grace',
        ],
        [
            'url'     => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/WeAreGoingOnBullrun.mp4',
            'caption' => '🐃 Wild nature #reel #nature #wild',
            'user'    => 'hugo',
        ],
    ];

    public function run(): void
    {
        foreach ($this->videos as $data) {
            $user = User::where('username', $data['user'])->first();
            if (!$user) continue;

            $post = Post::create([
                'user_id'          => $user->id,
                'caption'          => $data['caption'],
                'is_reel'          => true,
                'comments_enabled' => true,
                'likes_count'      => rand(10, 500),
                'comments_count'   => rand(2, 50),
            ]);

            PostMedia::create([
                'post_id' => $post->id,
                'path'    => $data['url'],
                'type'    => 'video',
                'order'   => 0,
            ]);
        }
    }
}
