<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('folders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('folder_name', 30);
            $table->text('url_path');
            $table->text('parent_path');
            $table->string('folder_status', 10)->default('available');
            $table->text('folder_flag')->default('public');
            $table->bigInteger('admin_id')->unsigned()->nullable();
            $table->foreign('admin_id')->references('id')->on('admin')->onDelete('set null');
            $table->biginteger('bidang_id')->unsigned()->nullable();
            $table->foreign('bidang_id')->references('id')->on('bidang')->onDelete('cascade');
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
        Schema::dropIfExists('folders');
    }
}
