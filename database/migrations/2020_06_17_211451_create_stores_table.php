<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('user_id')->references('id')->on('users')->onUpdate('cascade');
            $table->string('slug', 191)->unique();
            $table->longText('contact')->nullable(); // phone_number, email,
            $table->longText('description')->nullable();
            $table->bigInteger('visits')->default(0);
            $table->bigInteger('total_products')->default(0);
            $table->longText('preference')->nullable();
            $table->longText('address')->nullable();
            $table->longText('banner')->nullable();
            $table->longText('logo')->nullable();
            $table->longText('mobile_logo')->nullable();
            $table->longText('social')->nullable();
            $table->longText('currency')->nullable();

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
        Schema::dropIfExists('stores');
    }
}
