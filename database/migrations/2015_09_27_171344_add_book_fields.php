<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBookFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('books', function ($table) {
            $table->string('shortname')->nullable();
            $table->integer('pack')->default(10); // Ставим 10, чтобы было хоть что-то для тех, кто не понимает, зачем это необходимо
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
            $table->dropColumn('shortname');
            $table->dropColumn('pack');
        });
    }
}
