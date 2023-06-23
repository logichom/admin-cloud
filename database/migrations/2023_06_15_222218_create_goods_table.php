<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->id()->comment('流水號');
            $table->integer('category_id')->comment('類別編號');
            $table->integer('brand_id')->comment('品牌編號');
            $table->string('goods_name')->comment('商品名稱');
            $table->text('goods_img')->comment('商品圖片');
            $table->tinyInteger('is_show')->default(0)->comment('是否顯示');
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
        Schema::dropIfExists('goods');
    }
}
