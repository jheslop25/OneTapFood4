<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DietaryPreferences extends Model
{
    use HasFactory;

    public function user(){
        return $this->hasOne(User::class);
    }

    public function familyMember(){
        return $this->hasOne(FamilyMember::class);
    }
}
