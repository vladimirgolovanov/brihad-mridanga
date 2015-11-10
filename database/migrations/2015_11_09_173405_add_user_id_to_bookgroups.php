<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserIdToBookgroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookgroups', function ($table) {
            $table->integer('user_id')->unsigned()->nullable();
        });
        Schema::table('bookgroups', function ($table) {
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
        Schema::table('bookgroups', function ($table) {
            $table->dropForeign('books_user_id_foreign');
        });
        Schema::table('bookgroups', function ($table) {
            $table->dropColumn('user_id');
        });
    }
}
