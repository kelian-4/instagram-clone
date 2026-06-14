<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Models\StoryView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StoryController extends Controller
{
   
 // sharepost
  public function sharePost(\App\Models\Post $post)
{
    $thumbnail = $post->thumbnail();
    if (!$thumbnail) {
        return response()->json(['ok' => false, 'error' => 'Pas de média']);
    }

    Story::create([
        'user_id'    => Auth::id(),
        'media_path' => $thumbnail->path,
        'type'       => $thumbnail->type,
        'expires_at' => now()->addHours(24),
    ]);

    return response()->json(['ok' => true]);
}




 /** Upload d'une story */
    public function store(Request $request)
    {
        $request->validate([
            'media' => 'required|file|mimes:jpeg,png,jpg,gif,webp,mp4,mov|max:51200',
        ]);

        $file = $request->file('media');
        $type = str_starts_with($file->getMimeType(), 'video') ? 'video' : 'image';
        $path = $file->store('stories', 'public');

        Story::create([
            'user_id'    => Auth::id(),
            'media_path' => $path,
            'type'       => $type,
            'expires_at' => now()->addHours(24),
        ]);

        return back()->with('success', 'Story publiée !');
    }

    /** Voir une story + enregistrer la vue */
    public function show(Story $story)
    {
        // Marquer comme vue
        StoryView::firstOrCreate([
            'story_id' => $story->id,
            'user_id'  => Auth::id(),
        ]);

        return response()->json([
            'id'        => $story->id,
            'url'       => $story->mediaUrl(),
            'type'      => $story->type,
            'user'      => [
                'username'   => $story->user->username,
                'avatar_url' => $story->user->avatarUrl(),
            ],
            'created_at' => $story->created_at->diffForHumans(),
        ]);
    }

    /** Supprimer sa propre story */
    public function destroy(Story $story)
{
    \Illuminate\Support\Facades\Gate::authorize('delete', $story);
    \Illuminate\Support\Facades\Storage::disk('public')->delete($story->media_path);
    $story->delete();
    return back();
}
}
