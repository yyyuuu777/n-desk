<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatisticTable extends Migration
{
    public function up()
    {
        Schema::create( 'statistics', function( Blueprint $table )
        {
            //基本
            $table->increments( 'id' );
            $table->date( 'date' )->comment('日期节点');
            $table->unsignedSmallInteger( 'type' )->default( 1 )->comment( '类型：1每日数据，2累计数据' );
            //用户
            $table->integer( 'user_num' )->default( 0 );
            //财务
            $table->integer( 'money' )->default( 0 )->comment('充值统计：元。小数四舍五入');//int=10亿级//float=6位有效数字=仅十万级
            $table->integer( 'withdraw_num' )->default( 0 );
            $table->integer( 'withdraw_money' )->default( 0 )->comment('元');
            //文件
            $table->integer( 'file_num' )->default( 0 );//integer = 十亿级 ÷ 十万用户 = 每人十万文件。请限制用户的小文件数量
            $table->BigInteger( 'file_size' )->default( 0 )->comment( 'Byte' );//支持10TB级+
            //文件下载
            $table->integer( 'file_download_num' )->default( 0 );
            $table->BigInteger( 'file_download_size' )->default( 0 )->comment( 'Byte' );
            //时间
            $table->timestamps();
            //索引
            $table->index( 'date' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'statistics' );
    }
}
