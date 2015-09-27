<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserIdToBooksAndPersons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('books', function ($table) {
            $table->integer('user_id')->unsigned()->nullable();
        });
        Schema::table('books', function ($table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
        Schema::table('persons', function ($table) {
            $table->integer('user_id')->unsigned()->nullable();
        });
        Schema::table('persons', function ($table) {
            $table->foreign('user_id')->references('id')->on('users');
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
            $table->dropForeign('books_user_id_foreign');
        });
        Schema::table('books', function ($table) {
            $table->dropColumn('user_id');
        });
        Schema::table('persons', function ($table) {
            $table->dropForeign('persons_user_id_foreign');
        });
        Schema::table('persons', function ($table) {
            $table->dropColumn('user_id');
        });
    }
}
