<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CahngeRateDefaultValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rate_providers', function (Blueprint $table) {
            $table->integer('car_clean')->nullable()->change();
            $table->integer('staff_treated')->nullable()->change();
            $table->integer('fast_receipt_delivery')->nullable()->change();
            $table->string('customer_experience')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
