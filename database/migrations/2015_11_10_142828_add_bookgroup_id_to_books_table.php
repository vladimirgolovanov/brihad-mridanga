<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBookgroupIdToBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('books', function ($table) {
            $table->integer('bookgroup_id')->default(null)->nullable()->unsigned();
        });
        Schema::table('books', function ($table) {
            $table->foreign('bookgroup_id')->references('id')->on('bookgroups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('books', function ($table) {
            $table->dropForeign('books_bookgroup_id_foreign');
        });
        Schema::table('books', function ($table) {
            $table->dropColumn('booksgroup_id');
        });
    }
}
