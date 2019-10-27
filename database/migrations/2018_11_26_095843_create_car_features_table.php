<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_features', function (Blueprint $table) {
            $table->integer('car_id')->unsigned();
            $table->foreign('car_id')
                ->references('id')
                ->on('cars')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->integer('features_id')->unsigned();
            $table->foreign('features_id')
                ->references('id')
                ->on('features')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->primary(['car_id', 'features_id'], 'car_feature_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('car_features');
    }
}
