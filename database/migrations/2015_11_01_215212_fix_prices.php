<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixPrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('books', function($table) {
            $table->double('price_buy');
            $table->double('price');
            $table->double('price_shop');
        });
        Schema::table('operations', function($table) {
            $table->double('price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('books', function($table) {
            $table->dropColumn('price_buy');
            $table->dropColumn('price');
            $table->dropColumn('price_shop');
        });
        Schema::table('operations', function ($table) {
            $table->dropColumn('price');
        });
    }
}
