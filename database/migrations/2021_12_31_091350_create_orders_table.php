<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice', 16);
            // snap_token required for snap midtrans
            $table->string('snap_token', 36)->nullable();
            // total price order
            $table->decimal('gross_amount', 10, 2);

            /**
             * 
             *  Detection result by Fraud Detection System (FDS). Possible values are
             *  - accept : Approved by FDS.
             *  - challenge: Questioned by FDS. Note: Approve transaction to accept it or transaction gets automatically canceled during settlement.
             *  - deny: Denied by FDS. Transaction automatically failed.
            **/
            $table->string('fraud_status')->nullable();

            /**
             * 
             *  Bank (filled when payment_type = bank_transfer ). Possible values are
             *  - bni
             *  - bca
             *  - permata
             *  - bri
             * 
            **/
            $table->string('bank')->nullable();

            /**
             * 
             *  Payment type. Possible values are
             *  - gopay.
             *  - bank_transfer (bca, bni, permata, bri) mandiri using echannel payment_type
             *  - cstore: convenience store. (alfamart, indomart)
             *  - echannel: transfer mandiri
             * 
            **/
            $table->string('payment_type'); 

            // bank transfer via mandiri
            $table->string('bill_key')->nullable();
            $table->string('biller_code')->nullable();

            // payment_type cstore via indomart/alfamart
            $table->string('payment_code')->nullable();

            // payment_type "credit_card"
            $table->string('approval_code')->nullable();
            $table->string('card_type')->nullable();
            $table->string('masked_card')->nullable();
            
            // payment instruction document url
            $table->string('instruction_url')->nullable();

            // https://api-docs.midtrans.com/#status-code
            $table->string('status_code');

            // description of transaction status
            $table->string('status_message');
            $table->string('va_number')->nullable();

            $table->string('signature_key')->nullable();
            $table->timestamp('transaction_time');
            $table->timestamp('settlement_time')->nullable();
            /** 
             * Transaction status after charge card transaction. Possible values are
             *  - capture: Transaction is accepted by the bank and ready for settlement.
             *  - deny: transaction is denied by the bank or FDS.
             *  - authorize: card is authorized in Pre-authorization feature.
             *  - pending: Credit card is pending and you will need to rely on the http notification webhook to receive the final transaction status.
             **/
            $table->string('transaction_status');
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
        Schema::dropIfExists('orders');
    }
}
