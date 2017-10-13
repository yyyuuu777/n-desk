<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'users', function( Blueprint $table )
        {
            //基本
            $table->increments( 'id' );
            $table->string( 'email' )->unique( 'email' );
            $table->string( 'name' )->nullable()->unique( 'name' );//应产品设计“注册时无用户名”要求
            $table->integer( 'uid' )->default( 0 )->comment( '用户中心存储用户ID' );
            $table->string( 'password' );
            $table->string( 'head' );
            $table->smallInteger( 'status' )->default( 1 )->comment( '1正常，0禁用' );
            //文件
            $table->integer( 'file_number' )->default( 0 );
            $table->bigInteger( 'space' )->default( 0 )->comment( 'byte' );
            $table->smallInteger( 'file_permission' )->default( 1 )->comment( '用户文件权限:1,公开，2分享加密链接，3仅允许自己' );
            //会员
            $table->tinyInteger( 'is_vip' )->default( 0 );
            $table->timestamp( 'vip_datetime' )->nullable();
            //账户
            $table->float( 'balance', 8, 2 )->default( 0 )->comment( '用户余额' );
            $table->float( 'apply_balance', 8, 2 )->default( 0 )->comment( '正在申请提现金额' );
            $table->float( 'total_balance', 8, 2 )->default( 0 )->comment( '累计提现金额' );
            //时间
            $table->timestamp( 'last_login' )->nullable();
            $table->timestamps();
            $table->softDeletes();//软删除
            //索引
            $table->index( 'uid' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'users' );
    }
}
