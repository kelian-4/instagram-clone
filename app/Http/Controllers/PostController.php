<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostMedia;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
   
public function repost(\App\Models\Post $original)
{
    // Crée un nouveau post qui référence l'original
    $post = Post::create([
        'user_id'  => Auth::id(),
        'caption'  => $original->caption
            ? "🔁 Republié de @{$original->user->username}\n\n{$original->caption}"
            : "🔁 Republié de @{$original->user->username}",
        'location' => $original->location,
    ]);

    // Copie les médias (référence le même path)
    foreach ($original->media as $media) {
        \App\Models\PostMedia::create([
            'post_id' => $post->id,
            'path'    => $media->path,
            'type'    => $media->type,
            'order'   => $media->order,
        ]);
    }

    return response()->json(['ok' => true]);
}



 /** Feed principal */
    public function index()
{
    $user = Auth::user();
    $followingIds = $user->following()->pluck('users.id')->push($user->id);

    $posts = Post::with(['user','media','likes'])
        ->whereIn('user_id', $followingIds)
        ->latest()
        ->paginate(10);

    // Stories avec views préchargées
    $stories = \App\Models\Story::with(['user','views'])
        ->active()
        ->whereIn('user_id', $followingIds)
        ->get()
        ->groupBy('user_id');

    $suggestions = \App\Models\User::whereNotIn('id', $followingIds)
        ->withCount('followers')
        ->orderBy('followers_count','desc')
        ->limit(5)
        ->get();

    return view('feed.index', compact('posts','stories','suggestions'));
}
    /** Formulaire de création */
    public function create()
    {
        return view('posts.create');
    }

    /** Enregistrement du post */
    public function store(Request $request)
    {
        $request->validate([
            'caption'  => 'nullable|string|max:2200',
            'location' => 'nullable|string|max:255',
            'media'    => 'required|array|min:1|max:10',
            'media.*'  => 'file|mimes:jpeg,png,jpg,gif,webp,mp4,mov|max:51200',
        ]);

        $post = Post::create([
            'user_id'  => Auth::id(),
            'caption'  => $request->caption,
            'location' => $request->location,
        ]);

        // Enregistre chaque média dans l'ordre
        foreach ($request->file('media') as $index => $file) {
            $type = str_starts_with($file->getMimeType(), 'video') ? 'video' : 'image';
            $path = $file->store('posts', 'public');

            PostMedia::create([
                'post_id' => $post->id,
                'path'    => $path,
                'type'    => $type,
                'order'   => $index,
            ]);
        }

        return redirect()->route('feed')->with('success', 'Post publié !');
    }

    /** Page détail d'un post */
    public function show(Post $post)
    {
        $post->load(['user', 'media', 'comments.user', 'comments.replies.user', 'likes']);
        return view('posts.show', compact('post'));
    }

    /** Suppression */
    public function destroy(Post $post)
{
    // Remplacement de $this->authorize() par Gate::authorize()
    \Illuminate\Support\Facades\Gate::authorize('delete', $post);

    foreach ($post->media as $media) {
        \Illuminate\Support\Facades\Storage::disk('public')->delete($media->path);
    }

    $post->delete();

    return redirect()->route('feed')->with('success', 'Post supprimé.');
}

}
