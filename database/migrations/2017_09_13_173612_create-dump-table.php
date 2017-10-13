<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDumpTable extends Migration
{
    public function up()
    {
        Schema::create( 'dumps', function( Blueprint $table )
        {
            $table->increments( 'id' );
            $table->string( 'name' );
            $table->string( 'username' )->nullable()->comment( '第三方账号' );
            $table->string( 'password' )->nullable()->comment( '第三方密码' );
            $table->string( 'url' );
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
        Schema::dropIfExists( 'dumps' );
    }
}
