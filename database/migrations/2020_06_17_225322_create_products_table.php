<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->string('name');
            $table->unsignedInteger('category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
            $table->string('sku')->nullable();
            $table->longText('description')->nullable();
            $table->string('slug', 191)->unique();
            $table->double('price', 20, 2);
            $table->unsignedInteger('min_quantity')->default(1);
            $table->unsignedInteger('multiple')->default(1);
            $table->unsignedInteger('quantity')->default(0);
            $table->string('unit')->default('unit');
            $table->longText('properties')->nullable();
            $table->longText('images')->nullable();
            $table->longText('variants')->nullable();

            $table->string('store')->references('slug')->on('stores')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('deleted_by')->nullable()->index();
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
