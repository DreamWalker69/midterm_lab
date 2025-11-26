<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReplyController extends Controller
{
    /**
     * Store a newly created reply.
     */
    public function store(Request $request, Tweet $tweet)
    {
        $validated = $request->validate([
            'content' => ['required', 'string', 'max:280'],
        ], [
            'content.required' => 'Reply content is required.',
            'content.max' => 'Reply cannot exceed 280 characters.',
        ]);

        $tweet->replies()->create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
        ]);

        return redirect()->back()->with('success', 'Reply posted successfully!');
    }

    /**
     * Remove the specified reply.
     */
    public function destroy(Reply $reply)
    {
        // Check if user owns this reply
        if ($reply->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $reply->delete();

        return redirect()->back()->with('success', 'Reply deleted successfully!');
    }
}
