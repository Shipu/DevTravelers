<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventAssetsSellingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_assets_selling', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignInteger('user_id', 'users');
            $table->foreignInteger('event_id', 'events');
            $table->foreignInteger('asset_id', 'assets');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_assets_selling');
    }
}
