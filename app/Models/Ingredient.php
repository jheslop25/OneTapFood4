<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    public function recipe(){
        return $this->belongsToMany(Recipe::class);
    }

    public function cart(){
        return $this->belongsToMany(Cart::class);
    }

    public function pantry(){
        return $this->belongsToMany(Pantry::class);
    }
    
    public function conversions(){
        return $this->hasMany(IngredientConversion::class);
    }

    public function nutrients(){
        return $this->hasMany(IngredientNutrient::class);
    }
}
