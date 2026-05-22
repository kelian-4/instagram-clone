<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Post;
use Livewire\Component;
use Livewire\Attributes\Locked; // Requis pour sécuriser les IDs
use Illuminate\Support\Facades\Auth;

class CommentSection extends Component
{
    #[Locked]
    public int    $postId        = 0;

    #[Locked]
    public int    $postUserId    = 0;

    public int    $commentsCount = 0;
    public string $body          = '';

    public ?int   $replyTo       = null;
    public string $replyUsername = '';

    public function mount(Post $post): void
    {
        $this->postId        = $post->id;
        $this->postUserId    = $post->user_id;
        $this->commentsCount = (int) $post->comments_count;
    }

    public function setReply(int $commentId, string $username): void
    {
        $this->replyTo       = $commentId;
        $this->replyUsername = $username;
        $this->body          = '';
    }

    public function cancelReply(): void
    {
        $this->replyTo       = null;
        $this->replyUsername = '';
        $this->body          = '';
    }

    public function submit(): void
    {
        if (!Auth::check()) return;
        if (trim($this->body) === '') return;

        $this->validate(['body' => 'required|string|max:2200']);

        $post = Post::find($this->postId);
        if (!$post) return;

        $comment = Comment::create([
            'user_id'   => Auth::id(),
                                   'post_id'   => $this->postId,
                                   'parent_id' => $this->replyTo,
                                   'body'      => trim($this->body),
        ]);

        $post->increment('comments_count');
        $this->commentsCount++;

        // Gestion des notifications
        if ($this->replyTo) {
            // C'est une réponse : on notifie l'auteur du commentaire d'origine
            $parentComment = Comment::find($this->replyTo);
            if ($parentComment && $parentComment->user_id !== Auth::id()) {
                // Remplacer par ta notification spécifique aux réponses si nécessaire
                $parentComment->user->notify(
                    new \App\Notifications\PostCommented(Auth::user(), $post, $comment)
                );
            }
        } else {
            // C'est un commentaire principal : on notifie l'auteur du post
            if ($post->user_id !== Auth::id()) {
                $post->user->notify(
                    new \App\Notifications\PostCommented(Auth::user(), $post, $comment)
                );
            }
        }

        $this->body          = '';
        $this->replyTo       = null;
        $this->replyUsername = '';
    }

    public function deleteComment(int $commentId): void
    {
        $comment = Comment::find($commentId);
        if (!$comment) return;

        // Vérification sécurisée (impossible à tricher grâce à #[Locked])
        if (Auth::id() !== $comment->user_id && Auth::id() !== $this->postUserId) {
            return;
        }

        // Calcul du nombre total de commentaires à retirer (le parent + ses réponses)
        $totalDeleted = 1;
        if ($comment->parent_id === null) {
            // Si c'est un commentaire principal, on compte ses enfants en BDD
            $totalDeleted += Comment::where('parent_id', $comment->id)->count();
        }

        $post = Post::find($this->postId);
        if ($post) {
            $post->decrement('comments_count', $totalDeleted);
        }

        $this->commentsCount = max(0, $this->commentsCount - $totalDeleted);

        // La suppression du parent supprimera les enfants si la cascade est configurée en BDD
        $comment->delete();
    }

    public function render()
    {
        $comments = Comment::with(['user', 'replies.user'])
        ->where('post_id', $this->postId)
        ->whereNull('parent_id')
        ->oldest()
        ->get();

        return view('livewire.comment-section', compact('comments'));
    }
}
