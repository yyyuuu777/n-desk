<?php
/**
 * Created by PhpStorm.
 * User: like
 * Date: 19/9/2560
 * Time: 14:31 น.
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    const TYPE = 0;
    const TYPE_VIP = 1;
    const TYPE_CREDIT = 2;
    const TYPE_COUPON_VIP = 3;
    const TYPE_COUPON_CREDIT = 4;
    const STATUS_ACTIVE = 1;
    const STATUS_CLOSED = 0;
    const STATUS_OPEND = 1;
    const PERCENT_MAX = 100;

    public static function rules( $scene, $id = NULL )
    {
        $rules = [
            'save' => [
                'title' => 'required|between:2,8',
                'price' => 'required|numeric',
                'type' => 'required|integer',
                'status' => 'integer',
                'content' => 'required|integer',
                'percent' => 'numeric',
            ],
        ];

        return $rules[ $scene ];
    }

    public static function msg()
    {
        return [
            'title.required' => ':attribute必须传入',
            'title.between' => ':attribute长度必须介于:min与:max之间',
            'price.required' => ':attribute必须传入',
            'price.numeric' => ':attribute必须是数字',
            'type.required' => ':attribute必须传入',
            'type.integer' => ':attribute必须是整数',
            'status.required' => ':attribute必须传入',
            'status.integer' => ':attribute必须是整数',
            'content.required' => ':attribute必须传入',
            'content.integer' => ':attribute必须是整数',
            'percent.required' => ':attribute必须传入',
            'percent.numeric' => ':attribute必须是整数',
        ];
    }

}