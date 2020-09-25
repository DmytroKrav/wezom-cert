<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStolenCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stolen_cars', function (Blueprint $table) {
            $table->id();
            $table->string('user_name')->index();
            $table->string('gov_number')->unique()->index();
            $table->string('color_hex');
            $table->string('vin_code')->index();
            $table->string('maker_id');
            $table->string('model_id');
            $table->year('year');

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
        Schema::dropIfExists('stolen_cars');
    }
}
