<?php
/**
 * Created by PhpStorm.
 * User: like
 * Date: 5/9/2560
 * Time: 9:31 น.
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Dump extends Model
{
    use SoftDeletes;//软删除

    protected $guarded = [];

    public static function rules( $scene, $id = NULL )
    {
        $rules = [
            'insert' => [
                'name' => 'required|max:255|unique:dumps,name',
                'username' => 'required_with_all:password|max:255',
                'password' => 'required_with_all:username|max:255',
                'url' => 'required|url|max:255'
            ],
            'update' => [
                'id' => 'required|exists:dumps,id',
                'name' => 'required|max:255|unique:dumps,name,' . $id,
                'username' => 'required_with_all:password|max:255',
                'password' => 'required_with_all:username|max:255',
                'url' => 'required|url|max:255'
            ]
        ];

        return $rules[ $scene ];
    }

    public static function msg()
    {
        return [
            'id.required' => '站点ID不能为空',
            'id.exists' => '该站点不存在',
            'name.required' => '站点名称不能为空',
            'name.max' => '站点名称不能超过:max个字符',
            'name.unique' => '站点名称不能重复',
            'username.max' => '用户名不能超过:max个字符',
            'username.required_with_all' => '用户名必须填写，当参数里有:values时',
            'password.max' => '登录密码不能超过:max个字符',
            'password.required_with_all' => '登陆密码必须填写，当参数里有:values时',
            'url.required' => '站点地址不能为空',
            'url.url' => '站点地址必须是合法的http/https格式',
            'url.max' => '站点地址不能超过:max个字符'
        ];
    }

}