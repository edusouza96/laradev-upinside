<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn('sale');
        });
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn('rent');
        });
        Schema::table('contracts', function (Blueprint $table) {
            $table->string('purpose')->default('rent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->boolean('sale')->default(false);
        });
        Schema::table('contracts', function (Blueprint $table) {
            $table->boolean('rent')->default(false);
        });
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn('purpose');
        });
    }
}
