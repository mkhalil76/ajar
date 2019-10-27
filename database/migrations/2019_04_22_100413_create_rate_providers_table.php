<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRateProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rate_providers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('car_clean');
            $table->integer('staff_treated');
            $table->integer('fast_receipt_delivery');
            $table->string('customer_experience');
            $table->integer('status')->default(0);
            $table->Integer('user_id')->unsigned();
            $table->Integer('provider_id')->unsigned();
            $table->foreign('provider_id')
                ->references('id')
                ->on('providers')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('rate_providers');
    }
}
