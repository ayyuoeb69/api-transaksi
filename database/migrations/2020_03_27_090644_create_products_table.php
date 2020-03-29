<?php

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
            $table->bigIncrements('id');
            $table->unsignedBigInteger("merchant_id");
            $table->foreign('merchant_id')
                    ->references('id')->on('users')
                    ->onDelete('cascade');
            $table->string("name");
            $table->string("slug");
            $table->string("img");
            $table->integer("price");
            $table->longText("description");
            $table->integer("stock");
            $table->string("size",5);
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
        Schema::dropIfExists('products');
    }
}
