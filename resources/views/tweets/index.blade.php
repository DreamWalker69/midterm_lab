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
            <form action="{{ route('tweets.store') }}" method="POST" id="tweetForm" enctype="multipart/form-data">
                @csrf
                <textarea 
                    name="content" 
                    id="tweetContent"
                    rows="3" 
                    maxlength="280"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('content') border-red-500 @enderror"
                    placeholder="What's on your mind?"
                    required>{{ old('content') }}</textarea>
                
                <!-- Image Preview -->
                <div id="imagePreview" class="mt-3 hidden">
                    <div class="relative inline-block">
                        <img id="previewImg" src="" alt="Preview" class="max-h-64 rounded-lg border border-gray-300 dark:border-gray-600">
                        <button type="button" onclick="removeImage()" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                
                <div class="flex justify-between items-center mt-3">
                    <div class="flex items-center space-x-4">
                        <label for="tweetImage" class="cursor-pointer text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-500">
                            <i class="fas fa-image text-xl"></i>
                            <input type="file" 
                                   id="tweetImage" 
                                   name="image" 
                                   accept="image/*" 
                                   class="hidden"
                                   onchange="previewImage(event)">
                        </label>
                        <span id="charCount" class="text-sm text-gray-500 dark:text-gray-400">0 / 280</span>
                    </div>
                    <button type="submit" 
                            class="bg-blue-500 text-white px-6 py-2 rounded-full font-semibold hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 transition duration-200">
                        Tweet
                    </button>
                </div>
                
                @error('content')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
                @error('image')
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

                    <!-- Tweet Image -->
                    @if($tweet->image_path)
                        <div class="mb-4">
                            <img src="{{ $tweet->image_url }}" 
                                 alt="Tweet image" 
                                 class="rounded-lg max-h-96 w-full object-cover border border-gray-200 dark:border-gray-700">
                        </div>
                    @endif

                    <!-- Tweet Actions -->
                    <div class="flex items-center space-x-6 text-gray-500 dark:text-gray-400 border-t border-gray-200 dark:border-gray-700 pt-3">
                        @auth
                            <form action="{{ route('tweets.like', $tweet) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="flex items-center space-x-2 hover:text-red-500 transition">
                                    <i class="fas fa-heart {{ $tweet->isLikedBy(auth()->user()) ? 'text-red-500' : '' }}"></i>
                                    <span>{{ $tweet->likes_count }}</span>
                                </button>
                            </form>
                            <button onclick="toggleReplies('tweet-{{ $tweet->id }}')" class="flex items-center space-x-2 hover:text-blue-500 transition">
                                <i class="fas fa-comment"></i>
                                <span>{{ $tweet->replies_count }}</span>
                            </button>
                        @else
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-heart"></i>
                                <span>{{ $tweet->likes_count }}</span>
                            </div>
                            <button onclick="toggleReplies('tweet-{{ $tweet->id }}')" class="flex items-center space-x-2 hover:text-blue-500 transition">
                                <i class="fas fa-comment"></i>
                                <span>{{ $tweet->replies_count }}</span>
                            </button>
                        @endauth
                    </div>

                    <!-- Replies Section -->
                    <div id="replies-tweet-{{ $tweet->id }}" class="hidden mt-4 border-t border-gray-200 dark:border-gray-700 pt-4">
                        <!-- Reply Form (for authenticated users) -->
                        @auth
                            <form action="{{ route('replies.store', $tweet) }}" method="POST" class="mb-4">
                                @csrf
                                <div class="flex space-x-3">
                                    <div class="w-10 h-10 bg-gray-400 dark:bg-gray-600 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                    <div class="flex-1">
                                        <textarea 
                                            name="content" 
                                            rows="2" 
                                            maxlength="280"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm"
                                            placeholder="Write a reply..."
                                            required></textarea>
                                        <button type="submit" 
                                                class="mt-2 bg-blue-500 text-white px-4 py-1 rounded-full text-sm font-semibold hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 transition duration-200">
                                            Reply
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @endauth

                        <!-- Replies List -->
                        @if($tweet->replies->count() > 0)
                            <div class="space-y-3">
                                @foreach($tweet->replies as $reply)
                                    <div class="flex space-x-3 bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                        <div class="w-8 h-8 bg-gray-400 dark:bg-gray-600 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                            {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <a href="{{ route('profile.show', $reply->user) }}" class="font-semibold text-sm text-gray-900 dark:text-white hover:underline">
                                                        {{ $reply->user->name }}
                                                    </a>
                                                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">
                                                        {{ $reply->created_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                                @auth
                                                    @if($reply->user_id === auth()->id())
                                                        <form action="{{ route('replies.destroy', $reply) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this reply?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endauth
                                            </div>
                                            <p class="text-sm text-gray-800 dark:text-gray-200 mt-1 whitespace-pre-wrap">{{ $reply->content }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No replies yet. Be the first to reply!</p>
                        @endif
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

    // Image preview functionality
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('imagePreview').classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    }

    function removeImage() {
        document.getElementById('tweetImage').value = '';
        document.getElementById('imagePreview').classList.add('hidden');
        document.getElementById('previewImg').src = '';
    }

    // Toggle replies visibility
    function toggleReplies(tweetId) {
        const repliesSection = document.getElementById('replies-' + tweetId);
        repliesSection.classList.toggle('hidden');
    }
</script>
@endsection
