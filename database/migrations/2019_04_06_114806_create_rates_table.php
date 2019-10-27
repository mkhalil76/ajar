<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rates', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('provider_id')->unsigned();
            $table->Integer('user_id')->unsigned();
            $table->TinyInteger('is_draft')->default(0);
            $table->TinyInteger('is_cancel')->default(0);
            $table->TinyInteger('is_accident')->default(0);
            $table->Text('note');
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
        Schema::dropIfExists('rates');
    }
}
