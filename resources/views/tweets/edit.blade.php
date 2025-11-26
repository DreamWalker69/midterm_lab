@extends('layouts.app')

@section('title', 'Edit Tweet')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 transition-colors duration-200">
        <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Edit Tweet</h2>
        
        <form action="{{ route('tweets.update', $tweet) }}" method="POST" id="editTweetForm" enctype="multipart/form-data">
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

            <!-- Current Image Display -->
            @if($tweet->image_path)
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current Image</label>
                    <div id="currentImageContainer" class="relative inline-block">
                        <img src="{{ asset('storage/' . $tweet->image_path) }}" 
                             alt="Current tweet image" 
                             class="max-h-64 rounded-lg border border-gray-300 dark:border-gray-600">
                        <button type="button" 
                                onclick="markImageForRemoval()" 
                                class="absolute top-2 right-2 bg-red-500 text-white rounded-full px-3 py-1 text-sm hover:bg-red-600">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </div>
                    <input type="hidden" name="remove_image" id="removeImageInput" value="0">
                </div>
            @endif

            <!-- New Image Upload -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    {{ $tweet->image_path ? 'Change Image' : 'Add Image' }}
                </label>
                <div class="flex items-center space-x-4">
                    <label for="editTweetImage" class="cursor-pointer bg-gray-100 dark:bg-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                        <i class="fas fa-image text-blue-500"></i>
                        <span class="ml-2 text-gray-700 dark:text-gray-300">Choose Image</span>
                        <input type="file" 
                               id="editTweetImage" 
                               name="image" 
                               accept="image/*" 
                               class="hidden"
                               onchange="previewNewImage(event)">
                    </label>
                    <span id="fileName" class="text-sm text-gray-500 dark:text-gray-400"></span>
                </div>
                @error('image')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- New Image Preview -->
            <div id="newImagePreview" class="mb-4 hidden">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Image Preview</label>
                <div class="relative inline-block">
                    <img id="previewImg" src="" alt="Preview" class="max-h-64 rounded-lg border border-gray-300 dark:border-gray-600">
                    <button type="button" 
                            onclick="removeNewImage()" 
                            class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hover:bg-red-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
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

    // Image handling functions
    function previewNewImage(event) {
        const file = event.target.files[0];
        if (file) {
            document.getElementById('fileName').textContent = file.name;
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('newImagePreview').classList.remove('hidden');
            }
            reader.readAsDataURL(file);
            // Reset remove image flag if uploading new image
            document.getElementById('removeImageInput')?.setAttribute('value', '0');
        }
    }

    function removeNewImage() {
        document.getElementById('editTweetImage').value = '';
        document.getElementById('fileName').textContent = '';
        document.getElementById('newImagePreview').classList.add('hidden');
        document.getElementById('previewImg').src = '';
    }

    function markImageForRemoval() {
        if (confirm('Are you sure you want to remove this image?')) {
            document.getElementById('currentImageContainer').style.display = 'none';
            document.getElementById('removeImageInput').value = '1';
        }
    }
</script>
@endsection
