<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Traits\BuilderSearchTrait;
use App\Http\Controllers\Controller;
use App\Model\Permission;
use App\Helper\Traits\TokenTrait;

class PermissionController extends Controller
{

    use TokenTrait, BuilderSearchTrait;

    /**
     * 列表
     *
     * @param int    $id        ID
     * @param string $name      名称
     * @param string $route     路由
     * @param int    $level     层级
     * @param int    $parent_id 父ID：无父不传值
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function PermissionList()
    {
        $inputs = $this->request->input();

        $permissions = new Permission();
        $builder = $permissions->query();
        $permissions = $this->search( $builder, $inputs );

        return $this->responseJson( $permissions, '成功' );
    }

    /**
     * 删除
     *
     * @param int $id ID
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function PermissionDel( $id )
    {
        $permission = new Permission();
        if( !$permission = $permission->find( $id ) )
        {
            return $this->responseJson( [], '该权限不存在', 400 );
        }

        $permission->delete();//自动级联删除

        return $this->responseJson();
    }

    /**
     * 新增
     *
     * @param string $name      名称
     * @param string $route     路由
     * @param int    $level     层级
     * @param int    $parent_id 父ID：无父不传值
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function PermissionsAdd()
    {
        $inputs = $this->request->input();

        if( $error = $this->validateJson( $inputs, Permission::rules( 'insert' ), Permission::msg() ) )
            return $error;

        $permission = new Permission();
        $permission->Create( $inputs );
        $permission->save();

        return $this->responseJson();
    }

    /**
     * 修改
     *
     * @param int    $id        ID
     * @param string $name      名称
     * @param string $route     路由
     * @param int    $level     层级
     * @param int    $parent_id 父ID：无父不传值
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function PermissionSave()
    {
        $id = $this->request->input( 'id' );
        if( $error = $this->validateJson( $this->request->input(), Permission::rules( 'update', $id ), Permission::msg() ) )
            return $error;

        $permission = new Permission();
        $permission = $permission->find( $id );
        $permission->Create( $this->request->input() );
        $permission->save();

        return $this->responseJson();
    }

}
