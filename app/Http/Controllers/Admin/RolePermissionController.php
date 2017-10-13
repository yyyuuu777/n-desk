<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Traits\BuilderSearchTrait;
use App\Http\Controllers\Controller;
use App\Model\RolePermission;
use App\Helper\Traits\TokenTrait;

class RolePermissionController extends Controller
{

    use TokenTrait, BuilderSearchTrait;

    /**
     * 列表
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function List()
    {
        $inputs = $this->request->input();

        $role_permission = new RolePermission();
        $builder = $role_permission->query();
        $role_permission = $this->search( $builder, $inputs );

        return $this->responseJson( $role_permission, '成功' );
    }

    /**
     * 删除
     *
     * @param int $id ID
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function Del( $id )
    {
        if( !$role_permission = RolePermission::find( $id ) )
            return $this->responseJson( [], '角色不存在', 400 );

        if( !$role_permission->delete() )
            return $this->responseJson( [], '执行失败', 400 );

        return $this->responseJson();
    }

    /**
     * 新增
     *
     * @param int $role_id       角色ID
     * @param int $permission_id 权限ID
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function Add()
    {

        if( $error = $this->validateJson( $this->request->input(), RolePermission::rules( 'insert' ), RolePermission::msg() ) )
            return $error;

        $role_permission = new RolePermission();
        $role_permission->role_id = $this->request->input( 'role_id' );
        $role_permission->permission_id = $this->request->input( 'permission_id' );
        $role_permission->save();

        return $this->responseJson();
    }

}
