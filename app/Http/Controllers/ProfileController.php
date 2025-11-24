<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display the specified user's profile.
     */
    public function show(User $user)
    {
        $tweets = $user->tweets()
            ->with(['user', 'likes'])
            ->withCount('likes')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $totalLikesReceived = $user->totalLikesReceived();
        $totalTweets = $user->tweets()->count();

        return view('profile.show', compact('user', 'tweets', 'totalLikesReceived', 'totalTweets'));
    }
}
