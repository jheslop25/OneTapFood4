<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCycleMaxToCropGrowingDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crop_growing_data', function (Blueprint $table) {
            $table->string('cycle_max')->nullable()->after('cycle_min');
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
            //
        });
    }
}
