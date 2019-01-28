<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_participants', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignInteger('user_id', 'users');
            $table->foreignInteger('event_id', 'events');
            $table->integer('paid_amount')->nullable();
            $table->string('transaction_number')->nullable();
            $table->string('payment_mobile_number')->nullable();
            $table->tinyInteger('number_of_guest')->nullable();
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
        Schema::dropIfExists('event_participants');
    }
}
