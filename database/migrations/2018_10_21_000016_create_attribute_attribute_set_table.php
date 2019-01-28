<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttributeAttributeSetTable extends Migration
{
    /**
     * Run the migrations.
     * @table attribute_attribute_set
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attribute_attribute_set', function ( Blueprint $table ) {
            $table->foreignInteger('attribute_id', 'attributes');
            $table->foreignInteger('attribute_set_id', 'attribute_sets');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attribute_attribute_set');
    }
}
