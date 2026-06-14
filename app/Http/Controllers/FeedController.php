<?php
// Controller du fil d'actualité principal

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Story;
use App\Models\User;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // IDs des utilisateurs suivis + soi-même
        $followingIds = $user->following()->pluck('users.id')->push($user->id);

        // Posts paginés pour infinite scroll (15 par page)
        $posts = Post::with(['user', 'images', 'firstImage'])
            ->withCount('likes', 'comments')
            ->whereIn('user_id', $followingIds)
            ->latest()
            ->paginate(15);

        // Stories des utilisateurs suivis actives (non expirées)
        $stories = User::whereIn('id', $followingIds)
            ->whereHas('stories', fn($q) => $q->where('expires_at', '>', now()))
            ->with(['stories' => fn($q) => $q->where('expires_at', '>', now())->latest()->limit(1)])
            ->get();

        // Suggestions (utilisateurs non suivis)
        $suggestions = User::whereNotIn('id', $followingIds)
            ->withCount('followers')
            ->inRandomOrder()
            ->limit(5)
            ->get();

        return view('feed.index', compact('posts', 'stories', 'suggestions'));
    }
}
