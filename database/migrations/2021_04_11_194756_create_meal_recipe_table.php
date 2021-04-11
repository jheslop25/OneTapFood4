<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMealRecipeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meal_recipe', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('meal_id');
            $table->bigInteger('recipe_id');
            $table->float('servings', 4, 2); // this is num of servings of THIS recipe not over all meal.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meal_recipe');
    }
}
