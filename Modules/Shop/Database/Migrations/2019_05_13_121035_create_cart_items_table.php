<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('quantity')->unsigned()->default(0);
            $table->string('name')->nullable();
            $table->decimal('price', 12,4)->default(1);
            $table->decimal('total', 12,4)->default(0);
            $table->integer('product_id')->unsigned();
            $table->integer('cart_id')->unsigned();

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
        Schema::dropIfExists('cart_items');
    }
}
