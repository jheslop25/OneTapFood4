<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function mealplans(){
        return $this->hasMany(MealPlan::class);
    }

    public function cart(){
        return $this->hasMany(Cart::class);
    }

    public function pantry(){
        return $this->hasOne(Pantry::class);
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function familyMembers(){
        return $this->hasMany(FamilyMember::class);
    }

    public function dietaryPreferences(){
        return $this->hasOne(DietaryPreferences::class);
    }

    public function transactionPreferences(){
        return $this->hasOne(TransactionPreferences::class);
    }
}
