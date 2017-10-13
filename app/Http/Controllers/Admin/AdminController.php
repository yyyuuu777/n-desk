<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Traits\BuilderSearchTrait;
use App\Helper\Traits\TokenTrait;
use App\Http\Controllers\Controller;
use App\Model\Admin;
use App\Model\Permission;
use App\Model\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{

    use TokenTrait, BuilderSearchTrait;

    /**
     * 登录
     *
     * @param string $name     管理员名
     * @param string $password 密码
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $inputs = $this->request->input();

        if( $error = $this->validateJson( $inputs, Admin::rules( 'login' ), Admin::msg() ) )
            return $error;

        $admin = new Admin();
        $admin = $admin->where( 'name', $inputs[ 'name' ] )->first();

        if( $admin->status != Admin::STATUS_ACTIVE )
            return $this->responseJson( [], '该账号已禁用', 400 );

        if( !$admin->checkPassword( $inputs[ 'password' ] ) )
            return $this->responseJson( [], '密码错误', 400 );

        //登录成功
        return $this->responseJson( [
            'token' => $this->createToken( $admin->id ),
            'name' => $admin->name,
        ] );
    }

    /**
     * 退出
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->delToken( $this->request->user_id );

        return $this->responseJson( [], '退出成功', 200 );
    }

    /**
     * 列表
     *
     * @param int    $page    页码
     * @param string $email   邮箱
     * @param int    $status  状态
     * @param int    $role_id 角色ID
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function AdminList()
    {
        $inputs = $this->request->input();

        $admin = new Admin();
        $builder = $admin->query();
        $admins = $this->search( $builder, $inputs );

        return $this->responseJson( $admins, '成功' );
    }

    /**
     * 删除
     *
     * @param int $id ID：逗号分隔多个
     *
     * @return \Illuminate\Http\JsonResponse
     * @return \Illuminate\Http\JsonResponse
     */
    public function AdminDel( $id )
    {
        $arr_id = explode( ',', $id );
        $admin = new Admin();
        foreach( $arr_id as $_id )
        {
            $admin->find( $_id )->delete();
        }

        return $this->responseJson( [], '成功' );
    }

    /**
     * 保存：新增+修改
     *
     * @param int    $id      ID：逗号分隔多个
     * @param string $name    名字
     * @param int    $status  状态
     * @param int    $role_id 角色ID
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function AdminSave()
    {
        $admin = new Admin();

        if( $this->request->has( 'id' ) && $admin = $admin->find( $this->request->input( 'id' ) ) )
        {
            //修改：该用户ID存在
            if( $error = $this->validateJson( $this->request->input(), Admin::rules( 'update', $this->request->input( 'id' ) ), Admin::msg() ) )
                return $error;
        } else
        {
            //新增
            if( $error = $this->validateJson( $this->request->input(), Admin::rules( 'insert' ), Admin::msg() ) )
                return $error;
            $admin = new Admin();//重新创建对象
            $admin->password = Admin::getDefaultPassword();//默认密码
        }

        $admin->name = $this->request->input( 'name' ) ?? str_random( 6 );
        $admin->status = $this->request->input( 'status' ) ?? Admin::STATUS_ACTIVE;
        $admin->role_id = $this->request->input( 'role_id' ) ?? 1;
        $admin->save();

        return $this->responseJson();
    }

    /**
     * 密码重置
     *
     * @param string $admin_id 管理员ID
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword( $admin_id = 0 )
    {
        $admin = new Admin();
        if( !$admin = $admin->find( $admin_id ) )
        {
            return $this->responseJson( [], '该用户无法找到', 400 );
        }

        $admin->password = Admin::getDefaultPassword();
        $admin->save();

        return $this->responseJson();
    }

    /**
     * 密码修改
     *
     * @param string $password              新密码
     * @param string $password_confirmation 新密码验证
     * @param string $password_old          旧密码
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword()
    {
        if( $error = $this->validateJson( $this->request->input(), Admin::rules( 'updatePassword' ), Admin::msg() ) )
            return $error;

        $admin = new Admin();
        $admin = $admin->find( $this->request->user_id );

        if( !$admin->checkPassword( $this->request->input( 'password_old' ) ) )
            return $this->responseJson( [], '旧密码错误', 400 );

        $admin->password = Admin::getPassword( $this->request->input( 'password' ) );

        if( $admin->save() )
        {
            return $this->responseJson( [], '密码修改成功', 200 );
        } else
        {
            return $this->responseJson( [], '旧密码验证失败', 400 );
        }
    }

}
