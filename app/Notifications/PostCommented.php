<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostCommented extends Notification
{
    use Queueable;

    public function __construct(
        public User    $commenter,
        public Post    $post,
        public Comment $comment
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type'             => 'comment',
            'commenter_id'     => $this->commenter->id,
            'commenter_name'   => $this->commenter->username,
            'commenter_avatar' => $this->commenter->avatarUrl(),
            'post_id'          => $this->post->id,
            'post_thumb'       => $this->post->thumbnail()?->url(),
            'comment_body'     => substr($this->comment->body, 0, 80),
        ];
    }
}
