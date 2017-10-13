<?php
/**
 * Created by PhpStorm.
 * User: like
 * Date: 19/9/2560
 * Time: 16:16 น.
 */
namespace App\Http\Controllers\Admin;

use App\Helper\Traits\BuilderSearchTrait;
use App\Http\Controllers\Controller;
use App\Model\Coupon;
use Carbon\Carbon;

class CouponController extends Controller
{

    use BuilderSearchTrait;

    /**
     * 列表
     *
     * @return \Illuminate\Http\JsonResponse
     * status 状态码：0,未使用，1已使用，
     */
    public function CouponList()
    {
        $builder = Coupon::query();
        $coupons = $this->search( $builder, $this->request->input() );

        return $this->responseJson( $coupons );
    }

    /**
     * 生成
     *
     * @param int    $num             生成数量
     * @param string $date_begin      开始日期
     * @param string $date_end        结束日期
     * @param int    $package         套餐ID
     * @param int    $user_id_created 创建者ID（用户）（选填）
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function CouponAdd()
    {
        $inputs = $this->request->input();
        if( isset( $this->request->user_id ) )
            $inputs[ 'admin_id_created' ] = $this->request->user_id;

        if( $error = $this->validateJson( $inputs, Coupon::rules( 'insert' ), Coupon::msg() ) )
            return $error;

        $coupon = new Coupon();//创建对象
        $coupon->CreateCoupon( $inputs );

        return $this->responseJson();
    }

    /**
     * 删除
     *
     * @param int $id 优惠码ID（可多选，逗号分开）
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function CouponDel( $id )
    {
        $arr_ids = explode( ',', $id );
        foreach( $arr_ids as $arr_id )
        {
            Coupon::find( $arr_id )->delete();
        }

        return $this->responseJson();
    }

}
