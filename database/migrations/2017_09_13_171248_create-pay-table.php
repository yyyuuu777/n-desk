<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'orders', function( Blueprint $table )
        {
            $table->increments( 'id' );
            $table->string( 'order_number' )->unique();
            $table->string( 'order_number_third' )->nullable();
            $table->smallInteger( 'status' )->default( 1 )->comment( '状态：1，支付中，2,支付完成，3,支付失败，4订单关闭' );
            $table->float( 'price' );
            $table->unsignedInteger( 'user_id' );//无外键
            $table->unsignedInteger( 'package_id' );//无外键
            $table->unsignedInteger( 'pay_method_id' );//无外键
            $table->timestamps();
            $table->softDeletes();//软删除
            $table->index( 'order_number_third' );
            $table->index( 'status' );
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'orders' );
    }
}
