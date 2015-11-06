<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveBookprice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('bookprice');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // can't be undone
    }
}
