<?php
/**
 * Created by PhpStorm.
 * User: like
 * Date: 21/9/2560
 * Time: 11:01 น.
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;//软删除

    protected $guarded = [];

    const STATUS_PENDING = 1;
    const STATUS_DONE = 2;
    const STATUS_FAIL = 3;
    const STATUS_CLOSED = 4;

    public function payMethod()
    {
        return $this->hasOne( PayMethod::class );
    }

    public function statusName()
    {
        $arr = [
            self::STATUS_PENDING => '待支付',
            self::STATUS_DONE => '支付完成',
            self::STATUS_FAIL => '支付失败',
            self::STATUS_CLOSED => '订单关闭'
        ];

        return $arr[ $this->status ] ?? '';
    }

    /**
     * 用户关联
     */
    public function user()
    {
        return $this->belongsTo( 'App\Model\User', 'user_id' )->select( 'id', 'name', 'is_vip' );
    }

    /**
     * 套餐关联
     */
    public function package()
    {
        return $this->belongsTo( 'App\Model\Package', 'package_id' )->select( 'id', 'title', 'content' );
    }

    /**
     * 支付方式关联
     */
    public function pay_method()
    {
        return $this->belongsTo( 'App\Model\PayMethod', 'pay_method_id' )->select( 'id', 'name', 'channel' );
    }
}