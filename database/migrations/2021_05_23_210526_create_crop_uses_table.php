<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCropUsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crop_uses', function (Blueprint $table) {
            $table->id();
            $table->mediumInteger('crop_code');
            $table->string('used_part')->nullable();
            $table->string('detailed_use')->nullable();
            $table->string('main_use')->nullable();
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
        Schema::dropIfExists('crop_uses');
    }
}
