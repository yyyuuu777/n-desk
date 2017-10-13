<?php
/**
 * Created by PhpStorm.
 * User: like
 * Date: 20/9/2560
 * Time: 15:03 น.
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PayMethod extends Model
{

    protected $guarded = [];

    public static function SValidateColumn( $inputs )
    {
        $data = [];
        if( !isset( $inputs[ 'name' ] ) || strlen( $inputs[ 'name' ] ) < 2 )
        {
            $data[ 'name' ] = '支付方式名称必填(至少两个字符)';
        }

        if( !isset( $inputs[ 'channel' ] ) || strlen( $inputs[ 'channel' ] ) < 2 )
        {
            $data[ 'channel' ] = '支付渠道必填(至少两个字符)';
        }

        if( count( $data ) == 0 )
        {
            return TRUE;
        }

        return $data;
    }

}