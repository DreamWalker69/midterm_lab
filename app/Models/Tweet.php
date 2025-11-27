<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Tweet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content',
        'is_edited',
        'image_path',
    ];

    protected $casts = [
        'is_edited' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the tweet.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the likes for the tweet.
     */
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Get the replies for the tweet.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * Check if the tweet is liked by a specific user.
     */
    public function isLikedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return $this->likes()->where('user_id', $user->id)->exists();
    }

    /**
     * Get the like count for the tweet.
     */
    public function likesCount(): int
    {
        return $this->likes()->count();
    }

    /**
     * Get the full URL for the tweet image.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image_path) {
            return null;
        }

        // Extract just the filename from the path (remove 'tweets/' prefix)
        $filename = str_replace('tweets/', '', $this->image_path);
        
        return route('tweet.image', ['path' => $filename]);
    }
}
