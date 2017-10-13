<?php

use Illuminate\Database\Seeder;

/**
 * Created by PhpStorm.
 * User: jorah
 * Date: 10/3/2017 AD
 * Time: 15:57
 */

class UserTableSeeder extends Seeder
{
    public function  run() {

        $users = factory(\App\Model\User::class)->times(100)->make();
        \App\Model\User::insert($users->toArray());

    }

}