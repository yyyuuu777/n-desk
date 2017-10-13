<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get( '/', function() use ( $app )
{
    return $app->version();
} );

$app->group( [ 'namespace' => 'Admin', 'prefix' => 'admin' ], function() use ( $app )
{
    //管理员
    $app->post( 'login', 'AdminController@login' );//登录
} );

$app->group( [ 'namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => 'auth' ], function() use ( $app )
{

    //角色
    $app->get( 'role-list', 'RoleController@RoleList' );//列表
    $app->get( 'role-del/{id}', 'RoleController@RoleDel' );//删除
    $app->post( 'role-save', 'RoleController@RoleSave' );//保存

    //管理员
    $app->get( 'logout', 'AdminController@logout' );//退出
    $app->get( 'admin-del/{id}', 'AdminController@AdminDel' );//删除
    $app->get( 'admin-list', 'AdminController@AdminList' );//列表
    $app->post( 'admin-save', 'AdminController@AdminSave' );//保存

    //管理员密码
    $app->post( 'admin-password-reset/{admin_id}', 'AdminController@resetPassword' );//密码重置
    $app->post( 'admin-password-update', 'AdminController@updatePassword' );//密码修改

    //权限
    $app->get( 'permission-list', 'PermissionController@PermissionList' );//列表
    $app->get( 'permission-del/{id}', 'PermissionController@PermissionDel' );//删除
    $app->post( 'permission-add', 'PermissionController@PermissionsAdd' );//新增
    $app->post( 'permission-save', 'PermissionController@PermissionSave' );//编辑

    //角色-权限关联
    $app->get( 'role-permission-list', 'RolePermissionController@List' );//列表
    $app->get( 'role-permission-del/{id}', 'RolePermissionController@Del' );//删除
    $app->post( 'role-permission-add', 'RolePermissionController@Add' );//新增

    //用户
    $app->get( 'user-list', 'UserController@UserList' );//列表
    $app->get( 'user-frozen/{id}', 'UserController@UserFrozen' );//冻结

    //套餐
    $app->get( 'package-list', 'PackageController@PackageList' );//列表
    $app->get( 'package-coupon-list', 'PackageController@PackageCouponList' );//列表
    $app->get( 'package-del/{id}', 'PackageController@PackageDel' );//删除
    $app->post( 'package-save', 'PackageController@PackageSave' );//修改

    //优惠码
    $app->get( 'coupon-list', 'CouponController@CouponList' );//列表
    $app->get( 'coupon-del/{id}', 'CouponController@CouponDel' );//删除
    $app->post( 'coupon-add', 'CouponController@CouponAdd' );//增加

    //支付方式
    $app->get( 'pay-method-list', 'PayMethodController@PayMethodList' );//列表
    $app->get( 'pay-method-del/{id}', 'PayMethodController@PayMethodDel' );//删除
    $app->post( 'pay-method-save', 'PayMethodController@PayMethodSave' );//修改

    //订单
    $app->get( 'order-list', 'OrderController@OrderList' );//列表

    //提现
    $app->get( 'withdraw-list', 'WithdrawController@WithdrawList' );//列表=ftps
    $app->post( 'withdraw-req', 'WithdrawController@WithdrawReq' );//新增

    //文件
    $app->get( 'file-list', 'FileController@FileList' );//列表
    $app->post( 'file-del', 'FileController@FileDel' );//删除//前端需求post
    $app->post( 'file-save', 'FileController@FileSave' );//修改

    //转存站点
    $app->get( 'dump-list', 'DumpController@List' );//列表
    $app->get( 'dump-del/{id}', 'DumpController@Del' );//删除
    $app->get( 'dump-info/{id}', 'DumpController@Info' );//信息（单个）
    $app->post( 'dump-add', 'DumpController@Add' );//添加
    $app->post( 'dump-save', 'DumpController@Save' );//编辑

    //转存记录
    $app->get( 'dumplist-list', 'DumpListController@List' );//列表
    $app->get( 'dumping-list', 'DumpListController@DumpingList' );//正在转存列表
    $app->get( 'dumplist-info/{id}', 'DumpListController@Info' );//信息（单个）

    //转存记录
    $app->get( 'statistic-list', 'StatisticController@List' );//列表
    $app->get( 'statistic-info', 'StatisticController@Info' );//信息
    $app->post( 'statistic-save', 'StatisticController@Save' );//保存

} );

