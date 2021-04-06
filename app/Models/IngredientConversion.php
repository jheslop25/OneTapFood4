<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngredientConversion extends Model
{
    use HasFactory;

    public function ingredient(){
        return $this->belongsTo(Ingredient::class);
    }
}
