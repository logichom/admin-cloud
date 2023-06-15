<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSidebarMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sidebar_menu', function (Blueprint $table) {
            $table->id()->comment('流水號');
            $table->string('title')->comment('目錄名稱');
            $table->string('category_name')->comment('目錄分類');
            $table->integer('seq')->comment('排序');
            $table->integer('created_by_user_id')->comment('新增者編號');
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
        Schema::dropIfExists('sidebar_menu');
    }
}
