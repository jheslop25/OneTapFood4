<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecipesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->mediumInteger('summary')->nullable();
            $table->longText('description')->nullable();
            $table->integer('yield');
            $table->integer('prep_time');
            $table->integer('cook_time');
            $table->integer('total_time');
            $table->string('source_url');
            $table->float('total_cals', 8, 2)->default(0.00);
            $table->float('protein', 8, 2)->default(0.00);
            $table->float('fat', 8, 2)->default(0.00);
            $table->float('carbs', 8, 2)->default(0.00);
            $table->float('sat_fat', 8, 2)->default(0.00);
            $table->float('cholesterol', 8, 2)->default(0.00);
            $table->float('dietary_fibre', 8, 2)->default(0.00);
            $table->float('sodium', 8, 2)->default(0.00);
            $table->float('sugars', 8, 2)->default(0.00);
            $table->float('potassium', 8, 2)->default(0.00);
            $table->float('serving_weight_grams', 8,2)->default(0.00);
            $table->json('meal_types')->nullable();
            $table->json('diets')->nullable();
            $table->json('cuisines')->nullable();
            $table->bigInteger('spoon_id')->nullable();
            $table->boolean('gluten_free')->default(false);
            $table->boolean('vegan')->default(false);
            $table->boolean('vegetarian')->default(false);
            $table->boolean('dairy_free')->default(false);
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
        Schema::dropIfExists('recipes');
    }
}
