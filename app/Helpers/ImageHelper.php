<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ImageHelper
{
    public static function downloadAndStoreImage($url, $directory = 'recipe-images')
    {
        try {
            // Get the image content
            $context = stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ]);
            
            $imageContent = file_get_contents($url, false, $context);
            
            if ($imageContent === false) {
                throw new \Exception("Could not download image from URL");
            }

            // Create directory if it doesn't exist
            $storagePath = 'public/' . trim($directory, '/');
            $publicPath = storage_path('app/' . $storagePath);
            
            if (!file_exists($publicPath)) {
                mkdir($publicPath, 0755, true);
            }

            // Generate a unique filename
            $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
            $filename = Str::random(40) . '.' . $extension;
            $relativePath = $directory . '/' . $filename;
            $fullPath = $publicPath . '/' . $filename;

            // Save the image directly to the filesystem
            file_put_contents($fullPath, $imageContent);

            // Return the relative path for database storage
            return $relativePath;
        } catch (\Exception $e) {
            Log::error('Failed to download and store image: ' . $e->getMessage());
            return null;
        }
    }
}
