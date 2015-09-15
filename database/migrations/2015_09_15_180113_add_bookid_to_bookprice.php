<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBookidToBookprice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookprice', function ($table) {
            $table->integer('book_id')->default(0)->unsigned();
        });
        Schema::table('bookprice', function ($table) {
            $table->foreign('book_id')->references('id')->on('books');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookprice', function ($table) {
            $table->dropForeign('bookprice_book_id_foreign');
        });
        Schema::table('bookprice', function ($table) {
            $table->dropColumn('book_id');
        });
    }
}
