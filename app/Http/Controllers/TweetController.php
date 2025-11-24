<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TweetController extends Controller
{
    /**
     * Display a listing of all tweets (homepage).
     */
    public function index()
    {
        $tweets = Tweet::with(['user', 'likes'])
            ->withCount('likes')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('tweets.index', compact('tweets'));
    }

    /**
     * Store a newly created tweet.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => ['required', 'string', 'max:280'],
        ], [
            'content.required' => 'Tweet content is required.',
            'content.max' => 'Tweet cannot exceed 280 characters.',
        ]);

        Auth::user()->tweets()->create([
            'content' => $validated['content'],
        ]);

        return redirect()->route('home')->with('success', 'Tweet posted successfully!');
    }

    /**
     * Show the form for editing the specified tweet.
     */
    public function edit(Tweet $tweet)
    {
        // Check if user owns this tweet
        if ($tweet->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('tweets.edit', compact('tweet'));
    }

    /**
     * Update the specified tweet.
     */
    public function update(Request $request, Tweet $tweet)
    {
        // Check if user owns this tweet
        if ($tweet->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'content' => ['required', 'string', 'max:280'],
        ], [
            'content.required' => 'Tweet content is required.',
            'content.max' => 'Tweet cannot exceed 280 characters.',
        ]);

        $tweet->update([
            'content' => $validated['content'],
            'is_edited' => true,
        ]);

        return redirect()->route('home')->with('success', 'Tweet updated successfully!');
    }

    /**
     * Remove the specified tweet.
     */
    public function destroy(Tweet $tweet)
    {
        // Check if user owns this tweet
        if ($tweet->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $tweet->delete();

        return redirect()->route('home')->with('success', 'Tweet deleted successfully!');
    }
}
