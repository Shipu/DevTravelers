<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttributeSetsTable extends Migration
{
    /**
     * Run the migrations.
     * @table attribute_sets
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attribute_sets', function ( Blueprint $table ) {
            $table->increments('id');
            $table->string('name', 45)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attribute_sets');
    }
}
