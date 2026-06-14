<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /** ── Page principale messagerie ── */
    public function index(Request $request)
    {
        $me  = Auth::user();
        $tab = $request->get('tab', 'primary'); // primary | general | requests

        $allConvs = $me->conversations()
            ->with(['users', 'lastMessage.user'])
            ->get();

        // Sépare par folder/status du pivot de l'utilisateur courant
        $primary  = $allConvs->filter(fn($c) =>
            $c->users->firstWhere('id', $me->id)?->pivot->folder === 'primary'
            && $c->users->firstWhere('id', $me->id)?->pivot->status === 'accepted'
        );
        $general  = $allConvs->filter(fn($c) =>
            $c->users->firstWhere('id', $me->id)?->pivot->folder === 'general'
            && $c->users->firstWhere('id', $me->id)?->pivot->status === 'accepted'
        );
        $requests = $allConvs->filter(fn($c) =>
            $c->users->firstWhere('id', $me->id)?->pivot->status === 'pending'
        );

        $conversations = match($tab) {
            'general'  => $general,
            'requests' => $requests,
            default    => $primary,
        };

        return view('messages.index', compact(
            'conversations', 'tab',
            'primary', 'general', 'requests'
        ));
    }

    /** ── Ouvrir/créer une conversation ── */
    public function show(User $user)
    {
        $me = Auth::user();

        $conversation = Conversation::whereHas('users', fn($q) => $q->where('user_id', $me->id))
            ->whereHas('users', fn($q) => $q->where('user_id', $user->id))
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create();

            // Sender = accepted/primary, receiver = pending
            $conversation->users()->attach($me->id, [
                'folder' => 'primary',
                'status' => 'accepted',
            ]);
            $conversation->users()->attach($user->id, [
                'folder' => 'primary',
                'status' => $me->following->contains($user->id) ? 'accepted' : 'pending',
            ]);
        }

        // Marquer comme lu
        $conversation->messages()
            ->where('user_id', '!=', $me->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = $conversation->messages()
            ->with('user')
            ->oldest()
            ->get();

        $tab = 'primary';
        $allConvs = $me->conversations()->with(['users','lastMessage.user'])->get();
        $primary  = $allConvs->filter(fn($c) =>
            $c->users->firstWhere('id',$me->id)?->pivot->folder === 'primary'
            && $c->users->firstWhere('id',$me->id)?->pivot->status === 'accepted'
        );
        $general  = $allConvs->filter(fn($c) =>
            $c->users->firstWhere('id',$me->id)?->pivot->folder === 'general'
            && $c->users->firstWhere('id',$me->id)?->pivot->status === 'accepted'
        );
        $requests = $allConvs->filter(fn($c) =>
            $c->users->firstWhere('id',$me->id)?->pivot->status === 'pending'
        );
        $conversations = $primary;

        return view('messages.show', compact(
            'conversation', 'messages', 'user',
            'conversations', 'tab', 'primary', 'general', 'requests'
        ));
    }

    /** ── Envoyer un message ── */
    public function store(Request $request, Conversation $conversation)
    {
        $request->validate(['body' => 'required|string|max:1000']);

        abort_unless($conversation->users->contains(Auth::id()), 403);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'user_id'         => Auth::id(),
            'body'            => $request->body,
        ]);

        $message->load('user');

        if ($request->expectsJson()) {
            return response()->json(['message' => $message]);
        }
        return back();
    }

    /** ── Accepter une demande ── */
    public function acceptRequest(Request $request, Conversation $conversation)
    {
        $request->validate(['folder' => 'required|in:primary,general']);

        $conversation->users()->updateExistingPivot(Auth::id(), [
            'status' => 'accepted',
            'folder' => $request->folder,
        ]);

        return response()->json(['ok' => true, 'folder' => $request->folder]);
    }

    /** ── Refuser une demande ── */
    public function declineRequest(Conversation $conversation)
    {
        $conversation->users()->updateExistingPivot(Auth::id(), [
            'status' => 'declined',
        ]);
        return response()->json(['ok' => true]);
    }

    /** ── Bloquer ── */
    public function blockRequest(Conversation $conversation)
    {
        $conversation->users()->updateExistingPivot(Auth::id(), [
            'status' => 'blocked',
        ]);
        return response()->json(['ok' => true]);
    }

    /** ── Déplacer vers dossier ── */
    public function moveFolder(Request $request, Conversation $conversation)
    {
        $request->validate(['folder' => 'required|in:primary,general']);
        $conversation->users()->updateExistingPivot(Auth::id(), [
            'folder' => $request->folder,
        ]);
        return response()->json(['ok' => true]);
    }

    /** ── Partage de post en DM ── */
    public function sharePost(Request $request)
    {
        $request->validate([
            'user_id'  => 'required|exists:users,id',
            'post_id'  => 'required|exists:posts,id',
            'post_url' => 'required|string',
        ]);

        $me     = Auth::user();
        $target = User::findOrFail($request->user_id);

        $conversation = Conversation::whereHas('users', fn($q) => $q->where('user_id', $me->id))
            ->whereHas('users', fn($q) => $q->where('user_id', $target->id))
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create();
            $conversation->users()->attach($me->id,     ['folder'=>'primary','status'=>'accepted']);
            $conversation->users()->attach($target->id, ['folder'=>'primary','status'=>'pending']);
        }

        Message::create([
            'conversation_id' => $conversation->id,
            'user_id'         => $me->id,
            'body'            => '📷 Publication partagée : ' . $request->post_url,
        ]);

        return response()->json(['ok' => true]);
    }
}
