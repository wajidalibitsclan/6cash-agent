<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->decimal('plateform_fee')->nullable();
            $table->decimal('sender_fee')->nullable();
            $table->decimal('receiver_fee')->nullable();
            $table->decimal('admin_fee')->nullable();
            $table->decimal('receiver_amount')->nullable();
            $table->decimal('receiver_amount_exchange')->nullable();
            $table->string('customer_phone');
            $table->string('secret_pin');
            $table->enum('status', ['pending', 'cancel', 'complete'])->default('pending');
            $table->string('base_currency_code')->nullable();
            $table->string('destination_currency_code')->nullable();
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('receiver_id');
            $table->unsignedBigInteger('country_id');
            $table->unsignedBigInteger('city_id');
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
        Schema::dropIfExists('transaction_details');
    }
};
