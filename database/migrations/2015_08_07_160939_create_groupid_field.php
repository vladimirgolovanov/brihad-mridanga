<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupidField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('books', function ($table) {
            $table->integer('group_id')->default(0)->unsigned();
        });
        Schema::table('books', function ($table) {
            $table->foreign('group_id')->references('id')->on('groups');
        });
        Schema::table('persons', function ($table) {
            $table->integer('group_id')->default(0)->unsigned();
        });
        Schema::table('persons', function ($table) {
            $table->foreign('group_id')->references('id')->on('groups');
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
            $table->dropForeign('books_group_id_foreign');
        });
        Schema::table('books', function ($table) {
            $table->dropColumn('group_id');
        });
        Schema::table('persons', function ($table) {
            $table->dropForeign('persons_group_id_foreign');
        });
        Schema::table('persons', function ($table) {
            $table->dropColumn('group_id');
        });
    }
}
