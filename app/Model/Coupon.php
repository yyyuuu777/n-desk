<?php
/**
 * Created by PhpStorm.
 * User: like
 * Date: 19/9/2560
 * Time: 16:15 น.
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Package;

class Coupon extends Model
{

    protected $guarded = [];

    const STATUS_PENDING = 0;
    const STATUS_USED = 1;

    public function StatusName()
    {
        $arr = [
            self::STATUS_PENDING => '未使用',
            self::STATUS_USED => '已使用',
        ];

        return $arr[ $this->status ] ?? '';
    }

    /**
     * 获取优惠码字符串
     */
    public static function GetCode()
    {
        do
        {
            $str = str_random( 16 );
        } while( self::where( 'code', $str )->first() );

        return $str;
    }

    public static function rules( $scene, $id = NULL )
    {
        $rules = [
            'insert' => [
                'num' => 'numeric|between:1,99',
                'date_begin' => 'required|date',
                'date_end' => 'required|date',
                'package_id' => 'required|between:1,255|exists:packages,id',
                'admin_id' => 'between:1,255|exists:admins,id',
                'user_id_created' => 'between:1,255|exists:users,id'
            ],
        ];

        return $rules[ $scene ];
    }

    public static function msg()
    {
        return [
            'num.numeric' => '数量必须是一个数字',
            'num.between' => '数量必须介于:min到:max之间',
            'date_begin.required' => '开始日期必须输入',
            'date_begin.date' => '开始日期必须是yyyy-mm-dd日期格式',
            'date_end.required' => '结束日期必须输入',
            'date_end.date' => '结束日期必须是yyyy-mm-dd日期格式',
            'package_id.required' => '套餐ID必须输入',
            'package_id.between' => '套餐ID必须介于:min到:max之间',
            'package_id.exists' => '该套餐不存在',
            'admin_id_created.between' => '管理员ID必须介于:min到:max之间',
            'admin_id_created.exists' => '该管理员不存在',
            'user_id_created.between' => '用户ID必须介于:min到:max之间',
            'user_id_created.exists' => '该用户不存在',
        ];
    }

    public function CreateCoupon( $inputs )
    {
        foreach( range( 1, $inputs[ 'num' ] ) as $item )
        {
            self::create( [
                'code' => self::GetCode(),
                'status' => 0,
                'package_id' => $inputs[ 'package_id' ],
                'days' => $this->GetDays( $inputs[ 'package_id' ] ),
                'date_begin' => $inputs[ 'date_begin' ] ?? now(),
                'date_end' => $inputs[ 'date_end' ] ?? now(),
                'admin_id_created' => $inputs[ 'admin_id_created' ] ?? NULL,
                'user_id_created' => $inputs[ 'user_id_created' ] ?? NULL,
            ] );
        }
    }

    public function GetDays( $package_id )
    {
        $package = new Package();//创建对象

        return $package->find( $package_id )->content ?? 0;
    }

    /**
     * 创建者（管理员）关联
     */
    public function admin()
    {
        return $this->belongsTo( 'App\Model\Admin', 'admin_id_created' )->select( 'id', 'name' );
    }

    /**
     * 套餐关联
     */
    public function package()
    {
        return $this->belongsTo( 'App\Model\Package', 'package_id' )->select( 'id', 'title', 'content' );
    }

    /**
     * 使用者（用户）关联
     */
    public function user_used()
    {
        return $this->belongsTo( 'App\Model\User', 'user_id_used' )->select( 'id', 'name', 'is_vip' );
    }

}