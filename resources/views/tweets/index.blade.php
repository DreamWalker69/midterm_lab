@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2">
        <!-- Create Tweet Form (for authenticated users) -->
        @auth
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6 transition-colors duration-200">
            <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">What's happening?</h3>
            <form action="{{ route('tweets.store') }}" method="POST" id="tweetForm">
                @csrf
                <textarea 
                    name="content" 
                    id="tweetContent"
                    rows="3" 
                    maxlength="280"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('content') border-red-500 @enderror"
                    placeholder="What's on your mind?"
                    required>{{ old('content') }}</textarea>
                
                <div class="flex justify-between items-center mt-3">
                    <span id="charCount" class="text-sm text-gray-500 dark:text-gray-400">0 / 280</span>
                    <button type="submit" 
                            class="bg-blue-500 text-white px-6 py-2 rounded-full font-semibold hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 transition duration-200">
                        Tweet
                    </button>
                </div>
                
                @error('content')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </form>
        </div>
        @endauth

        <!-- Tweets List -->
        <div class="space-y-4">
            @forelse($tweets as $tweet)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 transition-colors duration-200">
                    <!-- Tweet Header -->
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-blue-500 dark:bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xl mr-3">
                                {{ strtoupper(substr($tweet->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <a href="{{ route('profile.show', $tweet->user) }}" class="font-semibold text-gray-900 dark:text-white hover:underline">
                                    {{ $tweet->user->name }}
                                </a>
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
                    <p class="text-gray-500 dark:text-gray-500 mt-2">Be the first to share something!</p>
                </div>
            @endforelse

            <!-- Pagination -->
            <div class="mt-6">
                {{ $tweets->links() }}
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 sticky top-6 transition-colors duration-200">
            <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">About</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">Welcome to Clario! Share your thoughts with the world.</p>
            
            @guest
                <div class="border-t pt-4">
                    <p class="text-gray-600 dark:text-gray-400 mb-3">Join the conversation</p>
                    <a href="{{ route('register') }}" 
                       class="block w-full bg-blue-500 dark:bg-blue-600 text-white text-center px-4 py-2 rounded-full font-semibold hover:bg-blue-600 dark:hover:bg-blue-700 transition duration-200">
                        Sign Up Now
                    </a>
                </div>
            @endguest
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Character counter for tweet form
    const textarea = document.getElementById('tweetContent');
    const charCount = document.getElementById('charCount');
    
    if (textarea && charCount) {
        textarea.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = length + ' / 280';
            
            if (length > 280) {
                charCount.classList.add('text-red-500');
                charCount.classList.remove('text-gray-500', 'dark:text-gray-400');
            } else {
                charCount.classList.add('text-gray-500', 'dark:text-gray-400');
                charCount.classList.remove('text-red-500');
            }
        });
    }
</script>
@endsection
