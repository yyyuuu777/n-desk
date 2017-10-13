<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'packages', function( Blueprint $table )
        {
            $table->increments( 'id' );
            $table->string( 'title' );
            $table->float( 'price' )->default( 0 );
            $table->unsignedSmallInteger( 'type' )->default( 1 )->comment( '类型：1,购买会员，2.购买积分，3兑换码兑换会员，4兑换码兑换积分' );
            $table->unsignedSmallInteger( 'status' )->default( 0 )->comment( '状态：0，关闭，1,开启' );
            $table->integer( 'content' )->default( 0 )->comment( '内容，type=1是购买会员的天数，type=2是购买的积分' );
            $table->float( 'percent' )->default( 0 )->comment( '折扣：%' );
            $table->timestamps();
            $table->softDeletes();//软删除
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
        Schema::dropIfExists( 'packages' );
    }
}
