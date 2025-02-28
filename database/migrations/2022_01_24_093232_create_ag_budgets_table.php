<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ag_budgets', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->uuid('budget_id')->nullable();
            // $table->unsignedBigInteger('permintaan_id');
            // $table->foreign('permintaan_id')->references('id')->on('permintaans')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('ag_permintaan_id');
            $table->foreign('ag_permintaan_id')->references('id')->on('ag_permintaans')->onDelete('cascade')->onUpdate('cascade');
            $table->string('group', 255)->nullable();
            $table->unsignedBigInteger('division_id');
            $table->foreign('division_id')->references('id')->on('divisions')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('asset_id');
            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('quantity');
            $table->integer('unitPrice');
            $table->integer('totalPrice')->nullable();
            $table->string('description', 255)->nullable();
            $table->string('createdBy', 255)->nullable();
            $table->dateTime('createdUtc')->nullable();
            $table->string('updatedBy', 255)->nullable();
            $table->dateTime('updatedUtc')->nullable();
            $table->string('deletedBy', 255)->nullable();
            $table->dateTime('deletedUtc')->nullable();
            $table->timestamps = false;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ag_budgets');
    }
}
