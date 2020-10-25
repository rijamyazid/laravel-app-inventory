<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logs', function (Blueprint $table){
            $table->dropColumn('folder_name');
            $table->dropColumn('file_name');
            $table->text('keterangan')->after('log_type');
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
            $table->string('folder_name', 30)->nullable();
            $table->string('file_name', 30)->nullable();
            $table->dropColumn('keterangan');
        });
    }
}
