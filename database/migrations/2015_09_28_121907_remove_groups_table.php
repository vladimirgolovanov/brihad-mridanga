<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('books', function ($table) {
            $table->dropForeign('books_group_id_foreign');
        });
        Schema::table('persons', function ($table) {
            $table->dropForeign('persons_group_id_foreign');
        });
        Schema::table('books', function ($table) {
            $table->dropColumn('group_id');
        });
        Schema::table('persons', function ($table) {
            $table->dropColumn('group_id');
        });
        Schema::drop('groups');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // can't bee undone
    }
}
