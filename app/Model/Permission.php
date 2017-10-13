<?php
/**
 * Created by PhpStorm.
 * User: like
 * Date: 11/9/2560
 * Time: 13:46 น.
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $guarded = [];

    public static function rules( $scene, $id = NULL )
    {
        $rules = [
            'insert' => [
                'name' => 'required|between:1,255|unique:permissions,name',
                'route' => 'required|between:1,255',
                'parent_id' => 'nullable|exists:permissions,id',
            ],
            'update' => [
                'id' => 'required|between:1,10|exists:permissions,id',
                'name' => 'required|between:1,255|unique:permissions,name,' . $id,
                'route' => 'required|between:1,255',
                'parent_id' => 'nullable|exists:permissions,id|not_in:' . $id,
            ],
        ];

        return $rules[ $scene ];
    }

    public static function msg()
    {
        return [
            'id.required' => 'ID不能为空',
            'id.between' => 'ID长度必须介于:min和:max之间',
            'id.exists' => 'ID不存在',
            'name.required' => '名称不能为空',
            'name.between' => '名称长度必须介于:min和:max之间',
            'name.unique' => '名称重复，请更换',
            'route.required' => '路由名称不能为空',
            'route.between' => '路由长度必须介于:min和:max之间',
            'parent_id.exists' => '父ID不存在，请确认正确ID',
            'parent_id.not_in' => '父ID不能指向自己',
        ];
    }

    /**
     * 父ID关联
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo( 'App\Model\Permission' );
    }

    //创建
    public function Create( $inputs )
    {
        $this->name = $inputs[ 'name' ];
        $this->route = $inputs[ 'route' ];

        $this->parent_id = $inputs[ 'parent_id' ] ?? NULL;
        if( $this->parent_id == '' )
            $this->parent_id = NULL;//兼容前端传空

        if( isset( $inputs[ 'parent_id' ] ) )
        {
            $permission_parent = new Permission();
            $permission_parent = $permission_parent->find( $inputs[ 'parent_id' ] );

            $this->level = ($permission_parent->level ?? 0) + 1;//自动计算层级

        } else
        {
            $this->level = 1;
        }
    }

}