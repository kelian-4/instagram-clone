<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class ReelController extends Controller
{
    public function index()
    {
        $reels = Post::with(['user', 'media', 'likes'])
            ->where('is_reel', true)
            ->has('media')
            ->latest()
            ->get();

        return view('reels.index', compact('reels'));
    }
}
