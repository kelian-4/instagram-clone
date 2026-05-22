<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    /** Toggle follow/unfollow */
    public function toggle(User $user)
    {
        $me = Auth::user();

        if ($me->id === $user->id) {
            return response()->json(['error' => 'Cannot follow yourself'], 422);
        }

        if ($me->isFollowing($user)) {
            $me->following()->detach($user->id);
            $following = false;
        } else {
            $me->following()->attach($user->id);
            $following = true;

            // Notification
            $user->notify(new \App\Notifications\NewFollower($me));
        }

        return response()->json([
            'following'       => $following,
            'followers_count' => $user->fresh()->followers()->count(),
        ]);
    }
}
