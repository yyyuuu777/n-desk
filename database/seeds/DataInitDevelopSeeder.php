<?php

use Illuminate\Database\Seeder;

class DataInitDevelopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //开发数据，正式环境不适用
        //$this->call('DataInitDevelopSeeder');//复制到DatabaseSeeder

        //管理员列表
        $admins = [];
        foreach( range( 1, 20 ) as $item )
        {
            $admins [] = [
                'name' => 'test' . $item,
                'role_id' => ( $item + 1 ) % 2 + 1,
                'status' => 1,
                'password' => \App\Model\Admin::getPassword( 111111 ),
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ];
        }
        DB::table( 'admins' )->insert( $admins );

        //角色列表
        $roles = [];
        foreach( range( 1, 20 ) as $item )
        {
            $roles [] = [
                'name' => 'test' . $item
            ];
        }
        DB::table( 'roles' )->insert( $roles );

        //权限列表
        foreach( range( 1, 20 ) as $item )
        {
            $permissions [] = [
                'name' => 'test' . $item,
                'route' => '/api/path/' . $item,
                'level' => $item,
                'parent_id' => ( $item == 1 ) ? NULL : $item - 1,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ];
        }
        DB::table( 'permissions' )->insert( $permissions );

        //角色权限列表
        $role_permissions = [];
        foreach( range( 1, 20 ) as $item )
        {
            $role_permissions [] = [
                'role_id' => $item,
                'permission_id' => $item,
            ];
        }
        DB::table( 'role_permissions' )->insert( $role_permissions );

        //用户列表
        foreach( range( 1, 20 ) as $item )
        {
            $users [] = [
                'uid' => $item,
                'name' => 'test' . $item,
                'password' => \App\Model\Admin::getPassword( 111111 ),
                'email' => $item . '@test.com',
                'head' => 'https://www.baidu.com/img/bd_logo1.png',
                'status' => 1,
                'file_number' => rand( 0, 100 ),
                'space' => rand( 1024 * 2, 1024 * 20 ),
                'balance' => rand( 0, 10000 ),
                'apply_balance' => rand( 0, 100 ),
                'total_balance' => rand( 0, 10000 ),
                'file_permission' => rand( 1, 3 ),
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ];
        }
        DB::table( 'users' )->insert( $users );

        //套餐列表
        foreach( range( 1, 20 ) as $item )
        {
            $packages [] = [
                'title' => 'test' . $item,
                'price' => rand( 10, 1000 ),
                'type' => $item % 4 + 1,
                'status' => 1,
                'content' => rand( 7, 365 ),
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ];
        }
        DB::table( 'packages' )->insert( $packages );

        //优惠码列表
        foreach( range( 1, 30 ) as $item )
        {
            $coupons [] = [
                'code' => 'test' . $item,
                'package_id' => $item % 2 + 3,
                'admin_id_created' => 1,
                'date_begin' => \Carbon\Carbon::now(),
                'date_end' => \Carbon\Carbon::now(),
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ];
        }
        DB::table( 'coupons' )->insert( $coupons );

        //支付方式列表
        $pay_method = [];
        foreach( range( 1, 3 ) as $item )
        {
            $pay_method [] = [
                'name' => 'test' . $item,
                'uid' => str_random( 6 ),
                'key' => str_random( 6 ),
                'status' => 1,
                'channel' => 'test' . $item,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ];
        }
        DB::table( 'pay_methods' )->insert( $pay_method );

        //订单列表
        foreach( range( 1, 60 ) as $item )
        {
            $orders [] = [
                'order_number' => 'test' . $item,
                'order_number_third' => 'test' . $item,
                'status' => rand( 1, 4 ),
                'price' => rand( 10, 1000 ),
                'package_id' => 1,
                'user_id' => 1,
                'pay_method_id' => 1,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ];
        }
        DB::table( 'orders' )->insert( $orders );

        //提现申请列表
        $withdraws = [];
        foreach( range( 1, 30 ) as $item )
        {
            $withdraws [] = [
                'user_id' => 1,
                'alipay' => str_random( 6 ),
                'money' => rand( 1, 100 ),
                'status' => rand( 1, 3 ),
                'remark' => 'no comment',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ];
        }
        DB::table( 'withdraws' )->insert( $withdraws );

        //文件列表
        $files = [];
        foreach( range( 1, 30 ) as $item )
        {
            $files [] = [
                'title' => 'test' . $item,
                'file_id' => str_random( 16 ),
                'file_path' => '/api/path/' . $item,
                'size' => rand( 1024, pow( 1024, 3 ) ),
                'status' => rand( 1, 4 ),
                'downloads' => rand( 1, 100 ),
                'sha1' => str_random( 32 ),
                'permission' => rand( 1, 3 ),
                'user_id' => 1,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ];
        }
        DB::table( 'files' )->insert( $files );

        //转存站点列表
        $dumps = [];
        foreach( range( 1, 3 ) as $item )
        {
            $dumps [] = [
                'name' => 'test' . $item,
                'username' => array_rand( [ NULL, 'test' ] ),
                'password' => array_rand( [ NULL, '111111' ] ),
                'url' => 'http://www.test.com',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ];
        }
        DB::table( 'dumps' )->insert( $dumps );

        //转存记录列表
        $dumplists = [];
        foreach( range( 1, 30 ) as $item )
        {
            $dumplists [] = [
                'title' => 'test' . $item,
                'size' => rand( 1, 1024 ),
                'user_id' => 1,
                'dump_id' => 1,
                'status' => 1,
                'use_time' => 0,
                'src' => 'http://www.test.com',
                'ip' => '0.0.0.0',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ];
        }
        DB::table( 'dump_lists' )->insert( $dumplists );
    }
}
