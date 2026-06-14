<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class ExploreController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q', '');
        $tag   = $request->get('tag', '');

        if ($query) {
            // Recherche users
            $users = User::where('username', 'like', "%{$query}%")
                ->orWhere('name', 'like', "%{$query}%")
                ->withCount('followers')
                ->limit(5)
                ->get();

            // Recherche posts par caption/hashtag
            $posts = Post::with(['user', 'media'])
                ->where('caption', 'like', "%{$query}%")
                ->latest()
                ->paginate(12);
        } elseif ($tag) {
            $users = collect();
            $posts = Post::with(['user', 'media'])
                ->where('caption', 'like', "%#{$tag}%")
                ->latest()
                ->paginate(12);
        } else {
            $users = collect();
            // Posts populaires par défaut
            $posts = Post::with(['user', 'media'])
                ->orderBy('likes_count', 'desc')
                ->paginate(24);
        }

        return view('explore.index', compact('posts', 'users', 'query', 'tag'));
    }
}
