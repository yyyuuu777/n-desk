<?php
/**
 * Created by PhpStorm.
 * User: like
 * Date: 20/9/2560
 * Time: 15:26 น.
 */

namespace App\Http\Controllers\Admin;

use App\Helper\Traits\BuilderSearchTrait;
use App\Http\Controllers\Controller;
use App\Model\Statistic;
use Carbon\Carbon;

class StatisticController extends Controller
{

    use BuilderSearchTrait;

    /**
     * 列表
     *
     * @param string $time 日期
     * @param int    $type 类型
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function List()
    {
        $inputs = $this->request->input();

        $builder = new Statistic();
        $builder = $builder->orderBy( 'date', 'desc' );
        $statistics = $this->search( $builder, $inputs );

        return $this->responseJson( $statistics );
    }

    /**
     * 添加
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function Save()
    {
        $date = $this->request->input( 'date' ) ??  Carbon::parse( '-1 days' )->toDateString();
        $type = $this->request->input( 'type' ) ?? Statistic::TYPE_DAILY;

        $statistic = new Statistic();
        $statistic->Statistic( $date, $type );

        if( $statistic->save() )
        {
            return $this->responseJson( $statistic );
        }
    }

    /**
     * 信息
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function Info()
    {
        $date = $this->request->input( 'date' ) ??  Carbon::parse( '-1 days' )->toDateString();//默认昨天
        $type = $this->request->input( 'type' ) ?? Statistic::TYPE_DAILY;//默认一天

        $statistic = new Statistic();
        $statistic = $statistic->orderBy( 'id', 'DESC' )->where( 'date', '=', $date )->where( 'type', '=', $type )->first();
        if( !$statistic )
        {
            $statistic = new Statistic();
            $statistic->Statistic( $date, $type );
            $statistic->save();
        }

        $statistic->SizeHuman();

        return $this->responseJson( $statistic );
    }

}