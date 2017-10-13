<?php

use Illuminate\Database\Seeder;

/**
 * Created by PhpStorm.
 * User: jorah
 * Date: 10/3/2017 AD
 * Time: 15:57
 */

class FileTableSeeder extends Seeder
{
    public function  run() {

        $files = factory(\App\Model\File::class)->times(100)->make();
        \App\Model\File::insert($files->toArray());

    }

}