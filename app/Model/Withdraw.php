<?php
/**
 * Created by PhpStorm.
 * User: like
 * Date: 20/9/2560
 * Time: 15:25 น.
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Withdraw extends Model
{
    use SoftDeletes;//软删除

    protected $guarded = [];

    const STATUS_ING = 1;
    const STATUS_PASS = 2;
    const STATUS_FAIL = 3;

    /**
     * 用户关联
     */
    public function user()
    {
        return $this->belongsTo( 'App\Model\User', 'user_id' )->select( 'id', 'name', 'email', 'is_vip' );
    }

    public static function ColumnValidate( $inputs )
    {
        $data = [];
        $id = $inputs[ 'id' ];
        if( !isset( $id ) || !Withdraw::find( $id ) )
        {
            $data[ 'id' ] = 'ID错误';
        }

        if( !isset( $inputs[ 'status' ] ) || !is_numeric( $inputs[ 'status' ] ) )
        {
            $data[ 'status' ] = '操作值必须为整数';
        }

        return empty( $data ) ? TRUE : $data;
    }

}