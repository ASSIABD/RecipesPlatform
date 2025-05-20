<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $recentRecipes = $user->recipes()->latest()->take(5)->get();
        $totalRecipes = $user->recipes()->count();
        $totalFavorites = $user->favorites()->count();
        $totalCategories = \App\Models\Category::count();

        return view('profiles.show', compact('user', 'recentRecipes', 'totalRecipes', 'totalFavorites', 'totalCategories'));
    }

    public function edit()
    {
        return view('profiles.edit');
    }

    public function update(Request $request)
    {
        try {
            $user = auth()->user();
            \Log::info('Profile update request received', [
                'user_id' => $user->id,
                'has_avatar' => $request->hasFile('avatar') ? 'Yes' : 'No',
                'files' => $request->allFiles()
            ]);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                try {
                    $image = $request->file('avatar');
                    
                    // Generate unique filename
                    $extension = $image->getClientOriginalExtension();
                    $imageName = 'avatar_' . $user->id . '_' . time() . '.' . $extension;
                    
                    // Store the file
                    $path = $image->storeAs('avatars', $imageName, 'public');
                    
                    if (!$path) {
                        throw new \Exception('Failed to store the uploaded file.');
                    }
                    
                    // Log the path for debugging
                    \Log::info('File stored successfully', [
                        'user_id' => $user->id,
                        'original_name' => $image->getClientOriginalName(),
                        'stored_path' => $path,
                        'full_path' => storage_path('app/public/' . $path)
                    ]);
                    
                    // Delete old avatar if it exists and is not the default
                    if ($user->avatar && $user->avatar !== 'avatars/default-avatar.jpg') {
                        try {
                            if (Storage::disk('public')->exists($user->avatar)) {
                                Storage::disk('public')->delete($user->avatar);
                                \Log::info('Old avatar deleted', [
                                    'user_id' => $user->id,
                                    'old_path' => $user->avatar
                                ]);
                            }
                        } catch (\Exception $e) {
                            \Log::warning('Failed to delete old avatar', [
                                'user_id' => $user->id,
                                'path' => $user->avatar,
                                'error' => $e->getMessage()
                            ]);
                        }
                    }
                    
                    // Update user record
                    $user->avatar = $path;
                    $user->avatar_cache = time();
                    
                } catch (\Exception $e) {
                    \Log::error('Avatar upload failed', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['avatar' => 'Failed to upload avatar: ' . $e->getMessage()]);
                }
            }

            // Update other profile information
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            
            // Save the user model
            $user->save();
            
            // Refresh the authenticated user's session data
            auth()->setUser($user->fresh());
            
            // Regenerate the session ID to prevent session fixation
            $request->session()->regenerate();
            
            // Log successful update
            \Log::info('Profile updated successfully', [
                'user_id' => $user->id,
                'avatar_path' => $user->avatar,
                'avatar_url' => $user->avatar_url
            ]);
            
            return redirect()->route('profile.edit')
                ->with('success', 'Profile updated successfully!');
                
        } catch (\Exception $e) {
            \Log::error('Profile update failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update profile: ' . $e->getMessage());
        }
    }
}
