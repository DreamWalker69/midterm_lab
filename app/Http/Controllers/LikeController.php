<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * Toggle like on a tweet.
     */
    public function toggle(Tweet $tweet)
    {
        $user = Auth::user();
        $like = $tweet->likes()->where('user_id', $user->id)->first();

        if ($like) {
            // Unlike the tweet
            $like->delete();
            $message = 'Tweet unliked.';
        } else {
            // Like the tweet
            $tweet->likes()->create([
                'user_id' => $user->id,
            ]);
            $message = 'Tweet liked!';
        }

        // Check if request expects JSON (for AJAX requests)
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'likes_count' => $tweet->likes()->count(),
                'is_liked' => !$like,
            ]);
        }

        return back()->with('success', $message);
    }
}
