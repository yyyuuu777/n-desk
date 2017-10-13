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
use App\Model\Package;

class PackageController extends Controller
{

    use BuilderSearchTrait;

    /**
     * 列表
     * @param int $type    类型：1,购买会员，2.购买积分，3兑换码兑换会员，4兑换码兑换积分，默认全部
     * @param int $status  状态：0关闭，1开启
     * @param int $content 内容：type=1是购买会员的天数，type=2是购买的积分
     * @return \Illuminate\Http\JsonResponse
     */
    public function PackageList()
    {
        $builder = Package::query();
        $packages = $this->search( $builder, $this->request->input() );

        return $this->responseJson( $packages );
    }

    /**
     * 优惠码用列表
     * @param int $type   类型：1,购买会员，2.购买积分，3.兑换码兑换会员，4.兑换码兑换积分，默认1,购买会员
     * @param int $status 状态：0关闭，1开启，默认全部
     * @return \Illuminate\Http\JsonResponse
     */
    public function PackageCouponList()
    {
        $inputs = $this->request->input();
        $inputs[ 'type' ] = $inputs[ 'type' ] ?? Package::TYPE_COUPON_VIP;

        $package = new Package();
        $builder = $package->query();
        $packages = $this->search( $builder, $inputs );

        return $this->responseJson( $packages );
    }

    /**
     * 保存=添加+编辑
     * @param string $title   标题
     * @param float  $price   价格
     * @param int    $type    类型：1,购买会员，2.购买积分，3兑换码兑换会员，4兑换码兑换积分，默认全部
     * @param int    $status  状态：0关闭，1开启
     * @param int    $content 内容：type=1是购买会员的天数，type=2是购买的积分
     * @param float  $percent 折扣%
     * @return \Illuminate\Http\JsonResponse
     */
    public function PackageSave()
    {
        $inputs = $this->request->input();

        if( $error = $this->validateJson( $inputs, Package::rules( 'save' ), Package::msg() ) )
            return $error;

        $package = new Package();
        if( $id = $this->request->input( 'id' ) )
        {
            $package = $package->find( $id );
            if( !$package )
                return $this->responseJson( [], 'ID不存在', 400 );
        }

        $package->title = $inputs[ 'title' ];
        $package->price = $inputs[ 'price' ] ?? 0;
        $package->type = $inputs[ 'type' ];
        $package->status = $inputs[ 'status' ] ?? Package::STATUS_ACTIVE;
        $package->content = $inputs[ 'content' ] ?? 0;
        $package->percent = $inputs[ 'percent' ] ?? Package::PERCENT_MAX;
        if( $package->save() )
            return $this->responseJson();

        return $this->responseJson( [], '保存失败', 400 );
    }

    /**
     * 删除
     * @param int $id ID：逗号分隔多个
     * @return \Illuminate\Http\JsonResponse
     */
    public function PackageDel( $id )
    {
        $arr_ids = explode( ',', $id );

        $package = new Package();
        foreach( $arr_ids as $arr_id )
        {
            if( !$package = $package->find( $arr_id ) )
                return $this->responseJson( [], '套餐ID不存在' . $arr_id, 400 );

            if( !$package->delete() )
                return $this->responseJson( [], '执行失败', 400 );
        }

        return $this->responseJson();
    }

}