<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function ingredients(){
        return $this->hasMany(Ingredient::class);
    }

    public function addToCart($ingredient, $amount){
        // handle conversion and addition here
    }

    public function removeFromCart($ingredient, $amount){
        // conversion and removal
    }

    public function empty(){
        //handle empty cart
    }
}
