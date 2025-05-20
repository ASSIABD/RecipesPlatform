<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Favorite;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'description', 'ingredients', 'steps', 'duration', 'difficulty', 'image', 'category_id'];
    
    protected $appends = ['image_url'];
    
    protected $casts = [
        'ingredients' => 'array',
        'steps' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function averageRating()
    {
        return $this->ratings()->avg('rating') ?? 0;
    }

    /**
     * Get all favorites for this recipe.
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'item_id')
            ->withTimestamps()
            ->where('item_type', self::class);
    }

    /**
     * Get all of the users who favorited this recipe.
     */
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'item_id', 'user_id')
            ->wherePivot('item_type', self::class);
    }
    
    /**
     * Get the full URL to the recipe image.
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        // If no image, return placeholder
        if (!$this->image) {
            return asset('images/placeholder.jpg');
        }
        
        // If it's an external URL, return it directly
        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }
        
        // For local files, build the URL manually
        $relativePath = ltrim($this->image, '/');
        $storagePath = 'public/' . $relativePath;
        $publicPath = 'storage/' . $relativePath;
        $fullPublicPath = public_path($publicPath);
        $fullStoragePath = storage_path('app/' . $storagePath);
        
        // Check if the symlink exists and is valid
        if (!is_link(public_path('storage'))) {
            Artisan::call('storage:link');
        }
        
        // Check if the file exists in the public storage directory
        if (file_exists($fullPublicPath)) {
            return asset($publicPath);
        }
        
        // Check if the file exists in the storage directory
        if (file_exists($fullStoragePath)) {
            // Ensure the directory exists in public/storage
            $targetDir = dirname($fullPublicPath);
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            // Copy the file to public storage
            copy($fullStoragePath, $fullPublicPath);
            return asset($publicPath);
        }
        
        // If we can't find the file, return a placeholder
        return asset('images/placeholder.jpg');
    }
}
