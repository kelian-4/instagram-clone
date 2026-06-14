<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\Post;
use App\Policies\PostPolicy;
use App\Models\Comment;
use App\Policies\CommentPolicy;
use App\Models\Story;
use App\Policies\StoryPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Policies enregistrées manuellement
     * (évite les problèmes de discovery sur NixOS)
     */
    protected $policies = [
        Post::class    => PostPolicy::class,
        Comment::class => CommentPolicy::class,
        Story::class   => StoryPolicy::class,
    ];

    public function register(): void {}

    public function boot(): void
    {
        // Pagination style Tailwind
        Paginator::useTailwind();

        // Enregistrement manuel des policies
        foreach ($this->policies as $model => $policy) {
            \Illuminate\Support\Facades\Gate::policy($model, $policy);
        }
    }
}
