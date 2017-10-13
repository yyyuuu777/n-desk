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

$app->group( [ 'namespace' => 'Portal', 'prefix' => 'portal','middleware' => 'cors'], function() use ( $app )
{
    //用户 user
    $app->post( 'login', 'UserController@login' );//登录
    $app->options( 'login', 'UserController@login' );//登录
    $app->post( 'register', 'UserController@register' );//注册
    $app->options( 'register', 'UserController@register' );//注册
    $app->get('file/search', 'FileController@FileSearch'); // 搜索
    $app->get('file/hot', 'FileController@FileHot'); // 搜索
} );

$app->group( [ 'namespace' => 'Portal', 'prefix' => 'portal', 'middleware' => 'auth' ], function() use ( $app )
{

    //用户 user
    $app->get( 'logout', 'UserController@logout' );//退出

    //用户修改
    $app->post( 'user/update', 'UserController@update' );//修改
    $app->post( 'user/update/name', 'UserController@updateName' );//名称
    $app->post( 'user/update/email', 'UserController@updateEmail' );//邮箱
    $app->post( 'user/update/password', 'UserController@updatePassword' );//密码
    $app->post( 'user/update/head', 'UserController@updateHead' );//头像

    // 搜索

} );
