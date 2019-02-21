<?php

use App\Enums\PaymentChannel;
use App\Enums\PaymentPurpose;
use App\Enums\PaymentStatus;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('status')->default(PaymentStatus::CREATE); // 0 = created, 1 = confirmed
            $table->tinyInteger('channel')->default(PaymentChannel::CASH); // 0 = Cash, 1 = bKash, 2 = Rocket, 3 = PortWallet, 4 = Bank, 5 = Cheque
            $table->tinyInteger('purpose')->default(PaymentPurpose::EVENT);
            $table->string('transaction_id')->nullable();
            $table->decimal('amount');
            $table->foreignIntegerNullable('event_id', 'events');
            $table->foreignIntegerNullable('sale_product_id', 'sale_products');
            $table->string('payment_mobile_number')->nullable();
            $table->nullableMorphs('sender');
            $table->nullableMorphs('receiver');
            $table->text('remarks')->nullable();
            $table->dateTime('paid_at');
            $table->text('api_result');
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
        Schema::dropIfExists('payments');
    }
}
