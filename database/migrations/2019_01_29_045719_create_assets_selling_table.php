<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetsSellingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets_selling', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignIntegerNullable('user_id', 'users');
            $table->foreignIntegerNullable('event_id', 'events');
            $table->foreignInteger('asset_attribute_id', 'asset_attributes');
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
        Schema::dropIfExists('assets_selling');
    }
}
