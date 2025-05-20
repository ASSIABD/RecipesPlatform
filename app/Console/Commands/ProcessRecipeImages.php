<?php

namespace App\Console\Commands;

use App\Models\Recipe;
use App\Helpers\ImageHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessRecipeImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recipes:process-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download and process external recipe images';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $recipes = Recipe::where('image', 'like', 'http%')->get();
        
        if ($recipes->isEmpty()) {
            $this->info('No recipes with external images found.');
            return 0;
        }

        $this->info("Processing {$recipes->count()} recipes with external images...");
        
        $bar = $this->output->createProgressBar($recipes->count());
        $bar->start();
        
        foreach ($recipes as $recipe) {
            try {
                if (str_starts_with($recipe->image, 'http')) {
                    $localPath = ImageHelper::downloadAndStoreImage($recipe->image);
                    if ($localPath) {
                        $recipe->image = $localPath;
                        $recipe->save();
                    }
                }
            } catch (\Exception $e) {
                Log::error("Failed to process image for recipe {$recipe->id}: " . $e->getMessage());
                $this->error("Error processing recipe {$recipe->id}: " . $e->getMessage());
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info('Finished processing recipe images.');
        
        return 0;
    }
}
