<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory; // <- ajoute cette ligne
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }
}
