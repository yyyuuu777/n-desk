<?php
/**
 * Created by PhpStorm.
 * User: like
 * Date: 11/9/2560
 * Time: 10:41 น.
 */
namespace App\Http\Controllers\Admin;

use App\Helper\Traits\BuilderSearchTrait;
use App\Http\Controllers\Controller;
use App\Model\User;

class UserController extends Controller
{

    use BuilderSearchTrait;

    /**
     * 用户列表
     * @param int    $per_page 每页个数
     * @param int    $page     页码
     * @param string $name     名字
     * @param string $email    邮箱
     * @param int    $status   状态
     * @param int    $is_vip   vip标记
     * @return \Illuminate\Http\JsonResponse
     */
    public function UserList()
    {
        $builder = User::query();
        $users = $this->search( $builder, $this->request->input() );

        return $this->responseJson( $users );
    }

    /**
     * 冻结/解冻用户
     * @param int $id ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function UserFrozen( $id )
    {
        if( !$user = User::find( $id ) )
        {
            return $this->responseJson( [], '无法找该用户', 400 );
        }

        if( $user->FrozenOrunfrozen() )
        {
            return $this->responseJson();
        }

        return $this->responseJson( [], '执行失败', 400 );
    }

}