<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserIdToPersongroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('persongroups', function ($table) {
            $table->integer('user_id')->unsigned()->nullable();
        });
        Schema::table('persongroups', function ($table) {
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
        Schema::table('persongroups', function ($table) {
            $table->dropForeign('persongroups_user_id_foreign');
        });
        Schema::table('persongroups', function ($table) {
            $table->dropColumn('user_id');
        });
    }
}
