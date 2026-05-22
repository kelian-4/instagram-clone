<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
public function show(string $username)
{
    $user = User::where('username', $username)
        ->with(['profile'])
        ->withCount(['posts', 'followers', 'following'])
        ->firstOrFail();

    $tab         = request('tab', 'posts');
    $isOwner     = Auth::check() && Auth::id() === $user->id;
    $isFollowing = Auth::check() && Auth::user()->isFollowing($user);
    $posts       = collect();

    return view('profile.show', compact(
        'user', 'posts', 'tab', 'isOwner', 'isFollowing'
    ));
}

    /** Formulaire édition profil */
    public function edit()
    {
        $user    = Auth::user()->load('profile');
        $profile = $user->profile ?? new Profile(['user_id' => $user->id]);
        return view('profile.edit', compact('user', 'profile'));
    }

    /** Mise à jour du profil */
    public function update(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'username'  => 'required|string|max:30|unique:users,username,' . Auth::id(),
            'email'     => 'required|email|unique:users,email,' . Auth::id(),
            'bio'       => 'nullable|string|max:150',
            'website'   => 'nullable|url|max:255',
            'full_name' => 'nullable|string|max:255',
            'avatar'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $user = Auth::user();

        // Mise à jour avatar
        if ($request->hasFile('avatar')) {
            // Supprime l'ancien
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
        ]);

        // Upsert du profil
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'bio'       => $request->bio,
                'website'   => $request->website,
                'full_name' => $request->full_name,
            ]
        );

        return redirect()->route('profile.show', $user->username)
            ->with('success', 'Profil mis à jour !');
    }
}
