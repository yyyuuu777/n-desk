<?php
/**
 * Created by PhpStorm.
 * User: like
 * Date: 5/9/2560
 * Time: 9:31 น.
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class RolePermission extends Pivot
{

    protected $guarded = [];

    public $timestamps = FALSE;

    public static function rules( $scene)
    {
        $rules = [
            'insert' => [
                'role_id' => 'required|between:1,10|exists:roles,id',
                'permission_id' => 'required|between:1,10|exists:permissions,id',
            ],
        ];

        return $rules[ $scene ];
    }

    public static function msg()
    {
        return [
            'role_id.required' => '角色ID必须输入',
            'role_id.between' => '角色ID必须介于:min到:max之间',
            'role_id.exists' => '角色ID不存在',
            'permission_id.required' => '权限ID必须输入',
            'permission_id.between' => '权限ID长度必须介于:min到:max之间',
            'permission_id.exists' => '权限ID不存在',
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

    /**
     * 权限关联
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function permission()
    {
        return $this->belongsTo( 'App\Model\Permission' );
    }

}