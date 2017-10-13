<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'admins', function( Blueprint $table )
        {
            $table->increments( 'id' );
            $table->string( 'name' )->unique();
            $table->string( 'password' );
            $table->string( 'email' )->nullable();
            $table->unsignedSmallInteger( 'status' )->default( 1 )->comment( '1,正常，2,禁用' );
            $table->unsignedInteger( 'role_id' )->nullable()->comment( '角色ID' );
            $table->timestamp( 'last_login' );
            $table->timestamps();
            $table->foreign( 'role_id' )->references( 'id' )->on( 'roles' )->onUpdate( 'CASCADE' )->onDelete( 'CASCADE' );//有外键
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'admins' );
    }
}
