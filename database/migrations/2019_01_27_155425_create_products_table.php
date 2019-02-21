<?php

use App\Enums\VisibilityStatus;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->nullable()->default(null);
            $table->string('slug');
            $table->longText('description')->nullable()->default(null);
            $table->double('price')->nullable()->default(null);
            $table->string('sku', 100)->unique();
            $table->integer('stock')->nullable()->default(0);
            $table->tinyInteger('status')->default(VisibilityStatus::ACTIVE);
            $table->foreignIntegerNullable('attribute_set_id', 'attribute_sets');
            $table->foreignIntegerNullable('parent_id', 'products'); // For variants
            $table->integer('order')->nullable();
            $table->auditColumn();
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
        Schema::dropIfExists('products');
    }
}
