<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions',function(Blueprint $table){
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('route');
            $table->unsignedSmallInteger('level')->default(1);
            $table->unsignedInteger('parent_id')->nullable()->comment('父ID');
            $table->timestamps();
            $table->foreign('parent_id')->references('id')->on('permissions')->onUpdate('CASCADE')->onDelete('CASCADE');//有外键：一致性要求高；不频繁写，分表压力小。后同
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}
