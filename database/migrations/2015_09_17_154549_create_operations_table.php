<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operations', function ($table) {
            $table->dateTime('datetime');
            $table->integer('person_id')->references('id')->on('persons');
            $table->integer('book_id')->references('id')->on('books');
            $table->integer('quantity');
            $table->tinyInteger('operation_type');
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
        Schema::drop('operations');
    }
}
