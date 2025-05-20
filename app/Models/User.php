<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
// Si Recipe, Comment, Rating, Favorite ne sont pas dans le même namespace App\Models, ajustez les imports.
// Mais généralement, ils le sont.
use App\Models\Recipe;    // Utilisé dans la relation recipes() et favoriteRecipes()
use App\Models\Comment;   // Utilisé dans la relation comments()
use App\Models\Rating;    // Utilisé dans la relation ratings()
use App\Models\Favorite;  // Utilisé dans la relation favorites() et favoriteRecipes()

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get all of the user's recipes.
     */
    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }

    /**
     * Get all of the user's favorite items.
     * (This seems to be a generic favorites relationship, ensure Favorite model is correctly set up)
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Get all of the user's favorite recipes.
     * (This is a polymorphic many-to-many relationship for favoriting recipes)
     */
    public function favoriteRecipes()
    {
        return $this->morphedByMany(Recipe::class, 'item', 'favorites')
            ->withTimestamps();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'bio',
        'avatar',
        'avatar_cache', // Assurez-vous que ce champ existe bien dans votre table 'users' via une migration.
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'avatar_cache' => 'integer',
        ];
    }

    /**
     * Get the URL to the user's avatar.
     *
     * @return string
     */
    /**
     * Get the URL to the user's avatar with cache busting
     *
     * @return string
     */
    public function getAvatarUrlAttribute()
    {
        // Default avatar path
        $defaultAvatar = asset('avatars/avatarInconnue.jpg') . '?v=' . ($this->avatar_cache ?? time());
        
        // Return default if no avatar is set
        if (empty($this->avatar)) {
            \Log::debug('Using default avatar', [
                'user_id' => $this->id,
                'reason' => 'No avatar set',
                'avatar_field' => $this->avatar
            ]);
            return $defaultAvatar;
        }
        
        // Handle external URLs
        if (str_starts_with($this->avatar, 'http')) {
            $url = $this->avatar . (str_contains($this->avatar, '?') ? '&' : '?') . 'v=' . ($this->avatar_cache ?? time());
            \Log::debug('Using external avatar URL', [
                'user_id' => $this->id,
                'url' => $url
            ]);
            return $url;
        }
        
        // Handle local storage
        try {
            // Remove any leading slashes from the path
            $cleanPath = ltrim($this->avatar, '/');
            
            // Check if the file exists in storage
            if (!Storage::disk('public')->exists($cleanPath)) {
                throw new \Exception("Avatar file not found in storage");
            }
            
            // Generate the URL with cache buster
            $url = asset('storage/' . $cleanPath) . '?v=' . ($this->avatar_cache ?? time());
            
            \Log::debug('Using local avatar', [
                'user_id' => $this->id,
                'path' => $cleanPath,
                'url' => $url,
                'exists' => Storage::disk('public')->exists($cleanPath) ? 'yes' : 'no'
            ]);
            
            return $url;
            
        } catch (\Exception $e) {
            \Log::error('Error generating avatar URL', [
                'user_id' => $this->id,
                'avatar' => $this->avatar,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return $defaultAvatar;
        }
    }

    // Le bloc de conflit qui était ici a été supprimé.
    // La méthode recipes() est déjà définie correctement plus haut.

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}