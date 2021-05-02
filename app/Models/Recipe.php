<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    public function meal(){
        return $this->belongsToMany(Meal::class);
    }

    public function ingredients(){
        return $this->belongsToMany(Ingredient::class);
    }

}