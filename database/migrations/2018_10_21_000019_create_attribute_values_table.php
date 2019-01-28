<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttributeValuesTable extends Migration
{
    /**
     * Run the migrations.
     * @table attribute_values
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attribute_values', function ( Blueprint $table ) {
            $table->increments('id');
            $table->foreignInteger('attribute_id', 'attributes');
            $table->string('value', 45)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attribute_values');
    }
}
