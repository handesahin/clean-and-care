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
            $table->bigInteger('user_id')->index();
            $table->string('order_number')->unique();
            $table->bigInteger('car_id')->index();
            $table->bigInteger('service_id')->index();
            $table->double('price');
            $table->string('note')->nullable();
            $table->enum('payment_method',["Balance","Cash","CreditCard"]);
            $table->enum('status',["Payment Pending","Paid","Canceled","In Progress","Completed"]);
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
