<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRainOptsToCropGrowingDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crop_growing_data', function (Blueprint $table) {
            $table->string('rain_opt_min')->nullable()->after('rain_abs_min');
            $table->string('rain_opt_max')->nullable()->after('rain_opt_min');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crop_growing_data', function (Blueprint $table) {
        });
    }
}
