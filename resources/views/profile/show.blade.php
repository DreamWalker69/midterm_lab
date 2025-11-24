@extends('layouts.app')

@section('title', $user->name . ' - Profile')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Profile Header -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 mb-6 transition-colors duration-200">
        <div class="flex items-start">
            <div class="w-24 h-24 bg-blue-500 dark:bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-4xl mr-6">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">{{ $user->email }}</p>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-2">
                    <i class="fas fa-calendar-alt"></i> 
                    Joined {{ $user->created_at->format('F Y') }}
                </p>
                
                <!-- Stats -->
                <div class="flex space-x-8 mt-4">
                    <div>
                        <span class="font-bold text-xl text-gray-900 dark:text-white">{{ $totalTweets }}</span>
                        <span class="text-gray-600 dark:text-gray-400 ml-1">{{ Str::plural('Tweet', $totalTweets) }}</span>
                    </div>
                    <div>
                        <span class="font-bold text-xl text-gray-900 dark:text-white">{{ $totalLikesReceived }}</span>
                        <span class="text-gray-600 dark:text-gray-400 ml-1">{{ Str::plural('Like', $totalLikesReceived) }} Received</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User's Tweets -->
    <div class="space-y-4">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Tweets by {{ $user->name }}</h2>
        
        @forelse($tweets as $tweet)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 transition-colors duration-200">
                <!-- Tweet Header -->
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-500 dark:bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xl mr-3">
                            {{ strtoupper(substr($tweet->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $tweet->user->name }}</span>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $tweet->created_at->diffForHumans() }}
                                @if($tweet->is_edited)
                                    <span class="text-xs italic">(edited)</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Edit/Delete buttons (only for tweet owner) -->
                    @auth
                        @if($tweet->user_id === auth()->id())
                            <div class="flex space-x-2">
                                <a href="{{ route('tweets.edit', $tweet) }}" 
                                   class="text-blue-500 hover:text-blue-700">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('tweets.destroy', $tweet) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this tweet?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>

                <!-- Tweet Content -->
                <p class="text-gray-800 dark:text-gray-200 mb-4 whitespace-pre-wrap">{{ $tweet->content }}</p>

                <!-- Tweet Actions -->
                <div class="flex items-center space-x-6 text-gray-500 dark:text-gray-400">
                    @auth
                        <form action="{{ route('tweets.like', $tweet) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center space-x-2 hover:text-red-500 transition">
                                <i class="fas fa-heart {{ $tweet->isLikedBy(auth()->user()) ? 'text-red-500' : '' }}"></i>
                                <span>{{ $tweet->likes_count }}</span>
                            </button>
                        </form>
                    @else
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-heart"></i>
                            <span>{{ $tweet->likes_count }}</span>
                        </div>
                    @endauth
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-12 text-center transition-colors duration-200">
                <i class="fas fa-comments text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-600 dark:text-gray-400">No tweets yet</h3>
                <p class="text-gray-500 dark:text-gray-500 mt-2">{{ $user->name }} hasn't posted anything yet.</p>
            </div>
        @endforelse

        <!-- Pagination -->
        <div class="mt-6">
            {{ $tweets->links() }}
        </div>
    </div>
</div>
@endsection
