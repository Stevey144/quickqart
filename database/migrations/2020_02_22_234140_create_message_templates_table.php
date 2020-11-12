<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug', 191)->unique();
            $table->string('description', 500)->nullable();

            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('deleted_by')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('variables', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('placeholder', 191)->unique();
            $table->string('description', 500)->nullable();

            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('deleted_by')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('message_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('message_id')
                ->references('id')->on('messages')->onDelete('cascade')->onUpdate('cascade');
            $table->string('version');
            $table->string('subject');
            $table->longText('template');
            $table->unsignedInteger('parent_id')->nullable()
                ->references('id')->on('message_templates')->onDelete('cascade')->onUpdate('cascade');
            $table->boolean('is_active')->default(false);

            $table->unsignedInteger('created_by')->nullable()->index();
            $table->unsignedInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('deleted_by')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('message_variables', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('message_id')
                ->references('id')->on('messages')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedInteger('variable_id')
                ->references('id')->on('variables')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('message_templates');
        Schema::dropIfExists('message_variables');
        Schema::dropIfExists('variables');
        Schema::dropIfExists('messages');
    }
}
