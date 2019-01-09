<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPersongroupIdToPersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('persons', function ($table) {
            $table->integer('persongroup_id')->default(null)->nullable()->unsigned();
        });
        Schema::table('persons', function ($table) {
            $table->foreign('persongroup_id')->references('id')->on('persongroups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('persons', function ($table) {
            $table->dropForeign('persons_persongroup_id_foreign');
        });
        Schema::table('persons', function ($table) {
            $table->dropColumn('personsgroup_id');
        });
    }
}
