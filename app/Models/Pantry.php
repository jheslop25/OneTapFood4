<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pantry extends Model
{
    use HasFactory;

    public function user(){
        return $this->belongsToMany(User::class); // we're gonna allow users to share a pantry
    }

    public function ingredients(){
        return $this->hasMany(Ingredient::class);
    }

    public function addToPantry($ingredient, $amount){
        //handle add etc
    }

    public function removeFromPantry($ingredient, $amount){
        //handle remove etc.
    }
}
