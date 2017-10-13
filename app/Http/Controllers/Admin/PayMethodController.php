<?php
/**
 * Created by PhpStorm.
 * User: like
 * Date: 20/9/2560
 * Time: 15:04 น.
 */

namespace App\Http\Controllers\Admin;

use App\Helper\Traits\BuilderSearchTrait;
use App\Http\Controllers\Controller;
use App\Model\PayMethod;

class PayMethodController extends Controller
{

    use BuilderSearchTrait;

    /**
     * 列表
     * @param string $name    名称
     * @param string $uid     UID（第三方）
     * @param string $key     KEY（第三方）
     * @param int    $status  状态
     * @param string $channel 渠道
     * @return \Illuminate\Http\JsonResponse
     */
    public function PayMethodList()
    {

        $builder = PayMethod::orderBy( 'id', 'desc' );
        $payMethod = $this->search( $builder, $this->request->input() );

        return $this->responseJson( $payMethod );
    }

    /**
     * 保存
     * @param int    $id      ID
     * @param string $name    名称
     * @param string $uid     UID（第三方）
     * @param string $key     KEY（第三方）
     * @param int    $status  状态
     * @param string $channel 渠道
     * @return \Illuminate\Http\JsonResponse
     */
    public function PayMethodSave()
    {

        if( $id = $this->request->input( 'id' ) )
        {
            $pay_method = PayMethod::find( $id );
            if( is_null( $pay_method ) )
            {
                return $this->responseJson( [], '无法找到该支付方式', 400 );
            }
        }
        else
        {
            $pay_method = new PayMethod();
        }

        $inputs = $this->request->input();
        $rs = PayMethod::SValidateColumn( $inputs );
        if( $rs !== TRUE )
        {
            return $this->responseJson( $rs, '验证失败', 400 );
        }

        $pay_method->name = $inputs[ 'name' ];
        $pay_method->uid = $inputs[ 'uid' ] ?? NULL;
        $pay_method->key = $inputs[ 'key' ] ?? NULL;
        $pay_method->status = $inputs[ 'status' ] ?? 0;
        $pay_method->channel = $inputs[ 'channel' ];

        $pay_method->save();

        return $this->responseJson();
    }

    /**
     * 删除
     * @param int $id ID：逗号分隔多个
     * @return \Illuminate\Http\JsonResponse
     */
    public function PayMethodDel( $id )
    {

        $arr = explode( ',', $id );
        foreach( $arr as $_id )
        {
            PayMethod::find( $_id )->delete();
        }

        return $this->responseJson();
    }

}