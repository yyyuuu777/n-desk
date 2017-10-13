<?php
/**
 * Created by PhpStorm.
 * User: like
 * Date: 5/9/2560
 * Time: 9:31 น.
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    protected $guarded = [];

    public $timestamps = FALSE;

    public static function rules( $scene, $id = NULL )
    {
        $rules = [
            'insert' => [
                'name' => 'required|between:1,255|unique:roles,name',
            ],
            'update' => [
                'id' => 'required|exists:roles,id',
                'name' => 'required|between:1,255|unique:roles,name,' . $id,
            ],
        ];

        return $rules[ $scene ];
    }

    public static function msg()
    {
        return [
            'id.required' => 'ID必须输入',
            'id.exists' => '该ID不存在',
            'name.required' => '名称必须输入',
            'name.between' => '名称长度必须介于:min到:max之间',
            'name.unique' => '名称已存在',
        ];
    }

    /**
     * 权限关联
     */
    public function permissions()
    {
        return $this->belongsToMany( 'App\Model\Permission', 'role_permissions', 'role_id', 'permission_id' );
    }

}