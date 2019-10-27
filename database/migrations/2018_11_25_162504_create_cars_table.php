<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->increments('id');
            $table->String('brand')->nullable();
            $table->String('type')->nullable();
            $table->String('model')->nullable();
            $table->boolean('can_delivery_in_another_branch')->default(false);
            $table->Integer('category');
            $table->Integer('picture')->default(1);
            $table->TinyInteger('status')->default(0);
            $table->integer('branch_id')->unsigned();
            $table->foreign('branch_id')
                ->references('id')
                ->on('branches')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->integer('provider_id')->unsigned();
            $table->foreign('provider_id')
                ->references('id')
                ->on('providers')
                ->onDelete('cascade')
                ->onUpdate('cascade');
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
        Schema::dropIfExists('cars');
    }
}
