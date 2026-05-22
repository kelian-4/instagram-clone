<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate(['body' => 'required|string|max:2200']);

        $comment = Comment::create([
            'user_id'   => Auth::id(),
            'post_id'   => $post->id,
            'parent_id' => $request->parent_id,
            'body'      => $request->body,
        ]);

        $post->increment('comments_count');

        // Notification
        if ($post->user_id !== Auth::id()) {
            $post->user->notify(
                new \App\Notifications\PostCommented(Auth::user(), $post, $comment)
            );
        }

        $comment->load('user');

        if ($request->expectsJson()) {
            return response()->json(['comment' => $comment]);
        }

        return back();
    }

	public function destroy(Comment $comment)
{
    \Illuminate\Support\Facades\Gate::authorize('delete', $comment);
    $comment->post->decrement('comments_count');
    $comment->delete();
    return back();
}
}
