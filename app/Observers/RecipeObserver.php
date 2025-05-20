<?php

namespace App\Observers;

use App\Models\Recipe;
use App\Helpers\ImageHelper;

class RecipeObserver
{
    public function saving(Recipe $recipe)
    {
        // Only process if the image is an external URL
        if ($recipe->isDirty('image') && $recipe->image) {
            if (str_starts_with($recipe->image, 'http')) {
                $localPath = ImageHelper::downloadAndStoreImage($recipe->image);
                if ($localPath) {
                    $recipe->image = $localPath;
                }
            }
        }
    }
}
