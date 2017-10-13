<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayTypeTable extends Migration
{

    public function up()
    {
        Schema::create( 'pay_methods', function( Blueprint $table )
        {
            $table->increments( 'id' );
            $table->string( 'name' );
            $table->string( 'uid' )->nullable()->default( '第三方提供uid' );
            $table->string( 'key' )->nullable()->default( '第三方提供key' );
            $table->unsignedSmallInteger( 'status' )->default( 0 )->comment( '状态： 0关闭，1开启' );
            $table->string( 'channel' )->default( '' )->comment( '渠道' );
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
        Schema::dropIfExists( 'pay_methods' );
    }
}
