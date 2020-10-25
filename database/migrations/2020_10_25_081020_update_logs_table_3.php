<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLogsTable3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logs', function (Blueprint $table){
            $table->biginteger('bidang_id')->unsigned()->nullable();
            $table->foreign('bidang_id')->references('id')->on('bidang')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logs', function (Blueprint $table){
            $table->dropForeign(['bidang_id']);
            $table->dropColumn('bidang_id');
        });
    }
}
