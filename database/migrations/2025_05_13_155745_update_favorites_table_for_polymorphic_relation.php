<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('favorites', function (Blueprint $table) {
            // Drop existing foreign key constraints
            $table->dropForeign(['user_id']);
            $table->dropForeign(['recipe_id']);
            
            // Drop the unique constraint
            $table->dropUnique(['user_id', 'recipe_id']);
            
            // Rename recipe_id to item_id
            $table->renameColumn('recipe_id', 'item_id');
            
            // Add item_type column for polymorphic relationship
            $table->string('item_type');
            
            // Re-add the foreign key for user_id
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            
            // Add a new unique constraint
            $table->unique(['user_id', 'item_id', 'item_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('favorites', function (Blueprint $table) {
            // Drop the unique constraint
            $table->dropUnique(['user_id', 'item_id', 'item_type']);
            
            // Drop the item_type column
            $table->dropColumn('item_type');
            
            // Rename item_id back to recipe_id
            $table->renameColumn('item_id', 'recipe_id');
            
            // Re-add the original unique constraint
            $table->unique(['user_id', 'recipe_id']);
            
            // Re-add the original foreign key constraints
            $table->foreign('recipe_id')
                  ->references('id')
                  ->on('recipes')
                  ->onDelete('cascade');
        });
    }
};
