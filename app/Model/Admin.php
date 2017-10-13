<?php
/**
 * Created by PhpStorm.
 * User: like
 * Date: 5/9/2560
 * Time: 9:31 น.
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{

    protected $guarded = [];


    /**
     * status 1 正常 0删除
     *
     * @var array
     */
    const STATUS_ACTIVE = 1;
    const STATUS_DISABLE = 2;
    const DEFAULT_PASSWORD = 111111;

    //转换密码
    public static function getPassword( $str )
    {
        return sha1( md5( $str ) );
    }

    //获取默认密码
    public static function getDefaultPassword()
    {
        return self::getPassword( self::DEFAULT_PASSWORD );
    }

    //验证密码
    public function checkPassword( $password )
    {
        return $this->password == self::getPassword( trim( $password ) );
    }

    public static function rules( $scene, $id = NULL )
    {
        $rules = [
            'login' => [
                'name' => 'required|between:1,255|exists:admins,name',
                'password' => 'required|between:6,20',
            ],
            'insert' => [
                'name' => 'required|between:1,255|unique:admins,name',
                'role_id' => 'exists:roles,id',
            ],
            'update' => [
                'id' => 'required|exists:admins,id',
                'name' => 'required|between:1,255|unique:admins,name,' . $id,
                'role_id' => 'exists:roles,id',
            ],
            'updatePassword' => [
                'password_old' => 'required|between:6,20',
                'password' => 'required|between:6,20|confirmed',
            ],
        ];

        return $rules[ $scene ];
    }

    public static function msg()
    {
        return [
            'id.required' => 'ID必须输入',
            'id.exists' => '该ID不存在',
            'name.required' => '名字必须输入',
            'name.between' => '名字长度必须介于:min到:max之间',
            'name.exists' => '该名字不存在',
            'name.unique' => '该名字已存在',
            'password.required' => '密码必须输入',
            'password.between' => '密码长度必须介于:min到:max之间',
            'password.confirmed' => '密码两次输入必须一致',
            'password_old.required' => '旧密码必须输入',
            'role_id.exists' => '角色ID不存在',
        ];
    }

    /**
     * 角色关联
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo( 'App\Model\Role' );
    }

}