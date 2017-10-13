<?php
/**
 * Created by PhpStorm.
 * User: like
 * Date: 14/9/2560
 * Time: 15:51 น.
 */

namespace App\Model;

use App\Helper\Traits\BuilderSearchTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class File extends Model
{

    protected $guarded = [];

    use SoftDeletes;
    use BuilderSearchTrait;

    const STATUS_NORMAL = 1;
    const STATUS_EXPIRED = 2;
    const STATUS_DELETE_BY_USER = 3;
    const STATUS_DELETE_BY_ADMIN = 4;

    // protected $appends = ['size_human'];

    public function getSizeHumanAttribute()
    {
        return $this->Size2Human($this->size);
    }

    /**
     * 用户关联
     */
    public function user()
    {
        return $this->belongsTo( 'App\Model\User', 'user_id' )->select( 'id', 'name', 'email', 'is_vip' );
    }

    /**
     * 下载量
     */
    public function getDownloadSize()
    {
        return $this->sum( DB::raw( 'downloads * size' ) );
    }


    public static function rules( $scene, $id = NULL ) {
      $rules = [
          'getHot' => [
              'type' => 'required | between:1,2'
          ]
      ];
        return $rules[ $scene ];
    }

    public static function msg() {
       return [
          'type.required' => '热门类型必须传入',
          'type.between' => '热门类型为1(查看)2(下载)'
       ];
    }


}