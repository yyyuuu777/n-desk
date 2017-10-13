<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileTable extends Migration
{

    public function up()
    {
        Schema::create( 'files', function( Blueprint $table )
        {
            //基本
            $table->increments( 'id' )->comment( 'ID：站内' );
            $table->string( 'title' );
            $table->unsignedSmallInteger( 'status' )->default( 1 )->comment( '状态：1正常，2过期，3用户删除，4系统删除' );
            $table->unsignedSmallInteger( 'type' )->comment( '类型' ); // .1 视频 2 图片 3 文档 4压缩包 5 其他
            $table->bigInteger( 'size' )->default( 0 )->comment( '大小：byte' );//扩展：1GB-10TB。linux/php取文件大小默认byte
            $table->string( 'sha1' );
            $table->integer( 'downloads' )->default( 0 )->comment( '下载次数' );
            $table->integer( 'view_num' )->default( 0 )->comment( '浏览次数' );
            //用户
            $table->unsignedInteger( 'user_id' )->nullable();//无外键
            $table->unsignedSmallInteger( 'permission' )->default( 1 )->comment( '权限:1公开，2加密链接，3仅自己' );
            $table->string( 'password' )->nullable()->comment( '下载密码' );
            //资源平台
            $table->integer( 'file_id' )->default( 0 )->comment( '资源平台返回ID' );
            $table->string( 'file_path' )->comment( '资源平台存储路径' );
            $table->timestamps();
            $table->softDeletes();//软删除
            $table->index( 'title' );
            $table->index( 'file_id' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'files' );
    }
}
