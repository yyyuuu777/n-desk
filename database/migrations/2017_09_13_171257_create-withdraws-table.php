<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWithdrawsTable extends Migration
{

    public function up()
    {
        Schema::create( 'withdraws', function( Blueprint $table )
        {
            $table->increments( 'id' );
            $table->unsignedInteger( 'user_id' );//无外键
            $table->string( 'alipay' )->comment( '提款账号（默认支付宝）' );
            $table->float( 'money' );
            $table->unsignedSmallInteger( 'status' )->default( 1 )->comment( '状态：1审核中，2,审核通过，3审核失败' );
            $table->timestamp( 'success_time' )->nullable()->comment( '审核通过时间' );
            $table->string( 'remark' )->default( '' )->comment( '审核备注，失败拒绝必须给出理由' );
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
        Schema::dropIfExists( 'withdraws' );
    }
}
