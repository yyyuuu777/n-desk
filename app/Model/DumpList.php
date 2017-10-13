<?php
/**
 * Created by PhpStorm.
 * User: like
 * Date: 5/9/2560
 * Time: 9:31 น.
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DumpList extends Model
{

    protected $guarded = [];

    const STAUTS_DOWNLOADING = 1;

    /**
     * 转存配置关联
     */
    public function dump()
    {
        return $this->belongsTo( 'App\Model\Dump', 'dump_id' )->select( 'id', 'name', 'username', 'url' );
    }

    /**
     * 用户关联
     */
    public function user()
    {
        return $this->belongsTo( 'App\Model\User', 'user_id' )->select( 'id', 'name', 'email', 'is_vip' );
    }

}