<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Bookmark;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    public function toggle(Post $post)
    {
        $user = Auth::user();

        $existing = Bookmark::where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $saved = false;
        } else {
            Bookmark::create(['user_id' => $user->id, 'post_id' => $post->id]);
            $saved = true;
        }

        return response()->json(['saved' => $saved]);
    }

    /** Page des posts sauvegardés */
   /* public function index()
    {
        $posts = Auth::user()
            ->bookmarks()
            ->with('post.media', 'post.user')
            ->latest()
            ->paginate(12);

        return view('bookmarks.index', compact('posts'));
	}*/
public function index()
{
    $bookmarks = Bookmark::where('user_id', auth()->id())->latest()->get();

    // On associe la clé 'bookmarks' à notre variable
    return view('bookmarks.index', [
        'bookmarks' => $bookmarks
    ]);
}
}
