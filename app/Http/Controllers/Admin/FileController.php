<?php
/**
 * Created by PhpStorm.
 * User: like
 * Date: 14/9/2560
 * Time: 15:49 น.
 */

namespace App\Http\Controllers\Admin;

use App\Helper\Traits\BuilderSearchTrait;
use App\Http\Controllers\Controller;
use App\Model\File;

class FileController extends Controller
{

    use BuilderSearchTrait;

    /**
     * 列表
     *
     * @param int    $page      页码
     * @param string $title     标题
     * @param string $user_name 用户名
     * @param int    $status    状态
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function FileList()
    {
        $inputs = $this->request->input();

        $builder = new File();
        $builder = $builder->orderBy( 'id', 'desc' );
        $builder = $builder->whereHas( 'user', function( $query ) use ( $inputs )
        {
            if( isset( $inputs[ 'user_name' ] ) )
                $query->where( 'name', $inputs[ 'user_name' ] );//关联查询
        } );
        $files = $this->search( $builder, $this->request->input() );

        return $this->responseJson( $files );
    }

    /**
     * 保存
     *
     * @param string $title            标题
     * @param string $password         密码
     * @param string $password_checked 密码开关
     * @param int    $permission       权限：:1公开，2分享加密链接，3仅自己
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function FileSave()
    {
        if( !$file = File::find( $this->request->input( 'id' ) ) )
        {
            return $this->responseJson( [], '无法找到该文件', 400 );
        }

        $file->title = $this->request->input( 'title' );
        if( $this->request->has( 'password_checked' ) )
        {
            $file->password = $this->request->input( 'password' );
        }

        $file->permission = $this->request->input( 'permission' );
        if( $file->save() )
        {
            return $this->responseJson();
        }

        return $this->responseJson( [], '操作失败', 400 );
    }

    /**
     * 删除：可批量
     *
     * @param int $id ID
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function FileDel()
    {
        $id = $this->request->input( 'id' ) ?? '';

        if( !is_array( $id ) )
            $id = explode( ',', $id );//兼容逗号字符串

        $file = new File();
        foreach( $id as $_id )
        {
            if( $file = $file->find( $_id ) )
            {
                $file->status = File::STATUS_DELETE_BY_ADMIN;
                if( !$file->save() )
                    return $this->responseJson( [], '操作失败', 400 );
            } else
            {
                return $this->responseJson( [], '无法找到该文件', 400 );
            }
        }

        return $this->responseJson();
    }

}