<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'role_permissions', function( Blueprint $table )
        {
            $table->increments( 'id' );
            $table->unsignedInteger( 'role_id' )->nullable();
            $table->unsignedInteger( 'permission_id' )->nullable();
            $table->foreign( 'role_id' )->references( 'id' )->on( 'roles' )->onUpdate( 'CASCADE' )->onDelete( 'CASCADE' );//有外键
            $table->foreign( 'permission_id' )->references( 'id' )->on( 'permissions' )->onUpdate( 'CASCADE' )->onDelete( 'CASCADE' );//有外键
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'role_permissions' );
    }
}
