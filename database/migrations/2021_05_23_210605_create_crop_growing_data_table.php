<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCropGrowingDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crop_growing_data', function (Blueprint $table) {
            $table->id();
            $table->mediumInteger('crop_code');
            $table->string('species')->nullable();
            $table->string('life_form')->nullable();
            $table->string('life_span')->nullable();
            $table->string('habit')->nullable();
            $table->string('physiology')->nullable();
            $table->string('category')->nullable();
            $table->string('attributes')->nullable();
            $table->string('temp_opt_min')->nullable();
            $table->string('temp_opt_max')->nullable();
            $table->string('temp_abs_min')->nullable();
            $table->string('temp_abs_max')->nullable();
            $table->string('rain_abs_min')->nullable();
            $table->string('rain_abs_max')->nullable();
            $table->string('lat_opt_min')->nullable();
            $table->string('lat_opt_max')->nullable();
            $table->string('lat_abs_min')->nullable();
            $table->string('lat_abs_max')->nullable();
            $table->string('alt_opt_min')->nullable();
            $table->string('alt_opt_max')->nullable();
            $table->string('alt_abs_min')->nullable();
            $table->string('alt_abs_max')->nullable();
            $table->string('ph_opt_min')->nullable();
            $table->string('ph_opt_max')->nullable();
            $table->string('ph_abs_min')->nullable();
            $table->string('ph_abs_max')->nullable();
            $table->string('light_opt_min')->nullable();
            $table->string('light_opt_max')->nullable();
            $table->string('light_abs_min')->nullable();
            $table->string('light_abs_max')->nullable();
            $table->string('depth_opt')->nullable();
            $table->string('depth_abs')->nullable();
            $table->string('soil_texture_opt')->nullable();
            $table->string('soil_texture_abs')->nullable();
            $table->string('fertility_opt')->nullable();
            $table->string('fertility_abs')->nullable();
            $table->string('al_toxicity_opt')->nullable();
            $table->string('al_toxicity_abs')->nullable();
            $table->string('salinity_opt')->nullable();
            $table->string('salinity_abs')->nullable();
            $table->string('drainage_opt')->nullable();
            $table->string('drainage_abs')->nullable();
            $table->string('climate_zone')->nullable();
            $table->string('photo_period')->nullable();
            $table->string('killing_temp_during_rest')->nullable();
            $table->string('killing_temp_early_growth')->nullable();
            $table->string('abiotic_tolerance')->nullable();
            $table->string('abiotic_suscept')->nullable();
            $table->string('introduction_risks')->nullable();
            $table->string('product_system')->nullable();
            $table->string('cropping_system')->nullable();
            $table->string('subsystem')->nullable();
            $table->string('companion_species')->nullable();
            $table->string('level_of_mechanization')->nullable();
            $table->string('labour_intensity')->nullable();
            $table->string('cycle_min')->nullable();
            $table->string('use_main')->nullable();
            $table->string('use_detailed')->nullable();
            $table->string('use_part')->nullable();
            $table->string('datasheet_url')->nullable();
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
        Schema::dropIfExists('crop_growing_data');
    }
}
