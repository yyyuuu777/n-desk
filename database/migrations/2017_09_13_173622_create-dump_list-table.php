<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDumpListTable extends Migration
{
    public function up()
    {
        Schema::create( 'dump_lists', function( Blueprint $table )
        {
            //基本
            $table->increments( 'id' );
            $table->string( 'title' );
            $table->bigInteger( 'size' )->nullable()->comment( '大小：byte' );//扩展
            $table->tinyInteger( 'status' )->default( 0 )->comment( '状态：0未开始，1下载中，2已完成，3失败' );
            //记录
            $table->integer( 'use_time' )->default( 0 )->comment( '用时：秒' );
            $table->string( 'src' )->nullable()->comment( '来源链接' );
            $table->string( 'ip' )->nullable()->comment( '发起人IP' );
            //关联
            $table->unsignedInteger( 'user_id' )->nullable();//无外键
            $table->unsignedInteger( 'dump_id' )->nullable();//无外键
            $table->timestamps();
            $table->softDeletes();//软删除
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'dump_lists' );
    }
}
