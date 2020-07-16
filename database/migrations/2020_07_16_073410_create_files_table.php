<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('uuid')->unique()->nullable();
            $table->text('filename')->nullable();
            $table->bigInteger('folder_id')->unsigned()->nullable();
            $table->foreign('folder_id')->references('id')->on('folders')->onDelete('cascade');
            $table->string('created_by', 20)->nullable();
            $table->foreign('created_by')->references('username')->on('admin')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
