<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Traits\BuilderSearchTrait;
use App\Http\Controllers\Controller;
use App\Model\Role;
use App\Helper\Traits\TokenTrait;

class RoleController extends Controller
{

    use TokenTrait, BuilderSearchTrait;

    /**
     * 列表
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function RoleList()
    {
        $inputs = $this->request->input();

        $builder = new Role();
        $builder = $builder->orderBy( 'id', 'asc' );
        $roles = $this->search( $builder, $inputs );

        return $this->responseJson( $roles );
    }

    /**
     * 删除
     *
     * @param int $id ID
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function RoleDel( $id )
    {
        if( !$role = Role::find( $id ) )
            return $this->responseJson( [], '角色不存在', 400 );

        if( !$role->delete() )
            return $this->responseJson( [], '执行失败', 400 );

        return $this->responseJson();
    }

    /**
     * 保存：新增+修改
     *
     * @param int    $id   ID
     * @param string $name 名称
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function RoleSave()
    {
        $role = new Role();
        if( $this->request->has( 'id' ) && $role = $role->find( $this->request->input( 'id' ) ) )
        {
            //修改
            if( $error = $this->validateJson( $this->request->input(), Role::rules( 'update', $this->request->input( 'id' ) ), Role::msg() ) )
                return $error;
        } else
        {
            //新增
            if( $error = $this->validateJson( $this->request->input(), Role::rules( 'insert' ), Role::msg() ) )
                return $error;
            $role = new Role();//重新创建对象
        }

        $role->name = $this->request->input( 'name' ) ?? '';
        $role->save();

        //关联
        $role->permissions()->sync( $this->request->input( 'permissions' ) ?? [] );//权限，前端传数组

        return $this->responseJson();
    }

}
