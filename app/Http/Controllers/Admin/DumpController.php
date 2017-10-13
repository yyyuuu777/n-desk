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
use App\Model\Dump;

class DumpController extends Controller
{

    use BuilderSearchTrait;

    /**
     * 列表
     *
     * @param int $per_page 每页个数
     * @param int $page     页码
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function List()
    {
        $builder = Dump::query();
        $coupons = $this->search( $builder, $this->request->input() );

        return $this->responseJson( $coupons );
    }

    /**
     * 新增
     *
     * @param string $name     名称
     * @param string $url      地址
     * @param string $username 用户名（来源站点）
     * @param string $password 密码（来源站点）
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function Add()
    {
        if( $error = $this->validateJson( $this->request->input(), Dump::rules( 'insert' ), Dump::msg() ) )
            return $error;

        $dump = new Dump();
        $dump = $dump->firstOrCreate( [ 'name' => $this->request->input( 'name' ) ] );//防止重名
        $dump->username = $this->request->input( 'username' );
        $dump->password = $this->request->input( 'password' );
        $dump->url = $this->request->input( 'url' );
        $dump->save();

        return $this->responseJson();
    }

    /**
     * 修改
     *
     * @param int    $id       ID
     * @param string $name     名称
     * @param string $url      地址
     * @param string $username 用户名（来源站点）
     * @param string $password 密码（来源站点）
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function Save()
    {
        if( $error = $this->validateJson( $this->request->input(), Dump::rules( 'update', $this->request->input( 'id' ) ), Dump::msg() ) )
            return $error;

        $dump = new Dump();
        $dump = $dump->find( $this->request->input( 'id' ) );
        $dump->name = $this->request->input( 'name' );
        $dump->username = $this->request->input( 'username' );
        $dump->password = $this->request->input( 'password' );
        $dump->url = $this->request->input( 'url' );
        $dump->save();

        return $this->responseJson();
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
        $dump = new Dump();
        if( !$dump = $dump->find( $id ) )
            return $this->responseJson( [], '该站点不存在', 400 );

        $dump->delete();

        return $this->responseJson();
    }

    /**
     * 查询
     *
     * @param int $id ID
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function Info( $id )
    {
        $dump = new Dump();
        if( !$dump = $dump->find( $id ) )
            return $this->responseJson( [], '该站点不存在', 400 );

        return $this->responseJson( $dump );
    }

}