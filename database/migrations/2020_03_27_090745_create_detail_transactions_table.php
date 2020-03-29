<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger("transaction_id");
            $table->foreign('transaction_id')
                    ->references('id')->on('transactions')
                    ->onDelete('cascade');
            $table->unsignedBigInteger("product_id");
            $table->foreign('product_id')
                    ->references('id')->on('products')
                    ->onDelete('cascade');
            $table->string("note")->nullable();
            $table->integer("qty");
            $table->integer("price");
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
        Schema::dropIfExists('detail_transactions');
    }
}
