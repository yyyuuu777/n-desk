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
use App\Model\DumpList;

class DumpListController extends Controller
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
        $builder = new DumpList();
        $builder = $builder->query();
        $dump_list = $this->search( $builder, $this->request->input() );

        return $this->responseJson( $dump_list );
    }

    /**
     * 列表
     *
     * @param int $per_page 每页个数
     * @param int $page     页码
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function DumpingList()
    {
        $inputs = $this->request->input();
        if( !$this->request->has( 'status' ) )
            $inputs[ 'status' ] = DumpList::STAUTS_DOWNLOADING;

        $builder = new DumpList();
        $builder = $builder->query();
        $dump_list = $this->search( $builder, $inputs );

        return $this->responseJson( $dump_list );
    }

    /**
     * 单条
     *
     * @param int $id ID
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function Info( $id )
    {
        $dump_list = new DumpList();
        if( !( $dump_list = $dump_list->with( 'user', 'dump' )->find( $id ) ) )
        {
            return $this->responseJson( [], '该站点不存在', 400 );
        }

        return $this->responseJson( $dump_list );
    }

}