<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserAvatarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get Assia's user
        $user = User::where('name', 'Assia Bendaou')->first();
        
        if ($user) {
            // Ensure the avatars directory exists
            $storagePath = storage_path('app/public/avatars');
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0777, true);
            }
            
            // Copy the default avatar to Assia's avatar
            $defaultAvatarPath = public_path('avatars/avatarInconnue.jpg');
            $newAvatarPath = storage_path('app/public/avatars/avatar_' . $user->id . '.jpg');
            
            if (file_exists($defaultAvatarPath)) {
                copy($defaultAvatarPath, $newAvatarPath);
                
                // Update the user's avatar path
                $user->avatar = 'avatars/avatar_' . $user->id . '.jpg';
                $user->save();
                
                // Create symlink if it doesn't exist
                if (!file_exists(public_path('storage'))) {
                    \Artisan::call('storage:link');
                }
            }
        }
    }
}
