<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    use HasFactory;

    public function mealplan(){
        return $this->belongsTo(MealPlan::class);
    }

    public function recipe(){
        return $this->hasMany(Recipe::class);
    }
}
