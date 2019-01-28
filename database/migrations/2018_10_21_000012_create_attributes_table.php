<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttributesTable extends Migration
{
    /**
     * Run the migrations.
     * @table attributes
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attributes', function ( Blueprint $table ) {
            $table->increments('id');
            $table->string('type', 50)->nullable();
            $table->string('name', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attributes');
    }
}
