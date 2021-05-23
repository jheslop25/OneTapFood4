<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCropAcademicDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crop_academic_data', function (Blueprint $table) {
            $table->id();
            $table->mediumInteger('crop_code');
            $table->string('authority')->nullable();
            $table->string('family')->nullable();
            $table->string('synonyms');
            $table->longText('common_names')->nullable();
            $table->string('editor')->nullable();
            $table->longText('notes')->nullable();
            $table->longText('sources')->nullable();
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
        Schema::dropIfExists('crop_academic_data');
    }
}
