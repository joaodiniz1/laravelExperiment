<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDealDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deal_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->datetime('hour');
            $table->integer('accepted');
            $table->integer('refused');
            $table->integer('client_deal_id');
            $table->foreign('client_deal_id')->references('id')->on('client_deals')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deal_details');
    }
}
