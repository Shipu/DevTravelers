<?php

use App\Enums\EventType;
use App\Enums\PaidType;
use App\Enums\VisibilityStatus;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->text('description');
            $table->string('location');
            $table->dateTime('start');
            $table->dateTime('end');
            $table->dateTime('registration_start');
            $table->dateTime('registration_end');
            $table->tinyInteger('event_type')->default(EventType::TOUR);
            $table->tinyInteger('paid_event')->default(PaidType::PAID);
            $table->integer('amount')->default(PaidType::PAID);
            $table->tinyInteger('approximate_amount')->default(VisibilityStatus::ACTIVE);
            $table->text('payment_options');
            $table->tinyInteger('status')->default(VisibilityStatus::ACTIVE);
            $table->string('remarks');
            $table->auditColumn();
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
        Schema::dropIfExists('events');
    }
}
