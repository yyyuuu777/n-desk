<?php

use Illuminate\Database\Seeder;

use Illuminate\Hashing\BcryptHasher;

class DataInitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //角色
        $role = [
            [
                'id' => 1,
                'name' => '管理员'
            ],
            [
                'id' => 2,
                'name' => '运营'
            ]
        ];
        DB::table( 'roles' )->insert( $role );

        //管理员
        $admin = [
            'id' => 1,
            'name' => 'admin',
            'role_id' => 1,
            'status' => 1,
            'password' => \App\Model\Admin::getPassword( 111111 ),
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ];
        DB::table( 'admins' )->insert( $admin );
    }
}
