<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call('DataInitSeeder');
        $this->call('DataInitDevelopSeeder');//开发数据，正式环境不使用
    }
}
