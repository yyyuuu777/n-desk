<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponTable extends Migration
{
    public function up()
    {
        Schema::create( 'coupons', function( Blueprint $table )
        {
            $table->increments( 'id' );
            $table->string( 'code' )->unique();
            $table->unsignedSmallInteger( 'status' )->default( 0 )->comment( '状态：0未使用，1,已使用，2,过期' );
            $table->unsignedInteger( 'package_id' )->comment( '兑换套餐ID' );
            $table->timestamp( 'date_begin' )->comment( '使用开始时间' );
            $table->timestamp( 'date_end' )->comment( '使用截止时间' );
            $table->unsignedInteger( 'admin_id_created' )->nullable()->comment( '创建者ID（管理员）' );//无外键：频繁写，分表压力大；不强制一致。请实现关联代码。后同。
            $table->unsignedInteger( 'user_id_created' )->nullable()->comment( '创建者ID（用户）' );//无外键
            $table->unsignedInteger( 'user_id_used' )->nullable()->comment( '使用者ID（用户）' );//无外键
            $table->timestamps();
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'coupons' );
    }
}
