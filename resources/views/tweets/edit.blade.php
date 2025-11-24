@extends('layouts.app')

@section('title', 'Edit Tweet')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 transition-colors duration-200">
        <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Edit Tweet</h2>
        
        <form action="{{ route('tweets.update', $tweet) }}" method="POST" id="editTweetForm">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <textarea 
                    name="content" 
                    id="editTweetContent"
                    rows="5" 
                    maxlength="280"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('content') border-red-500 @enderror"
                    required>{{ old('content', $tweet->content) }}</textarea>
                
                <div class="flex justify-between items-center mt-2">
                    <span id="editCharCount" class="text-sm text-gray-500 dark:text-gray-400"></span>
                </div>
                
                @error('content')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('home') }}" 
                   class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-200">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-blue-500 dark:bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-600 dark:hover:bg-blue-700 transition duration-200">
                    Update Tweet
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Character counter for edit form
    const textarea = document.getElementById('editTweetContent');
    const charCount = document.getElementById('editCharCount');
    
    function updateCharCount() {
        const length = textarea.value.length;
        charCount.textContent = length + ' / 280';
        
        if (length > 280) {
            charCount.classList.add('text-red-500');
            charCount.classList.remove('text-gray-500', 'dark:text-gray-400');
        } else {
            charCount.classList.add('text-gray-500', 'dark:text-gray-400');
            charCount.classList.remove('text-red-500');
        }
    }
    
    textarea.addEventListener('input', updateCharCount);
    updateCharCount(); // Initial count
</script>
@endsection
