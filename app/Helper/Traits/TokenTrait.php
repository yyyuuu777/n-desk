<?php

namespace App\Helper\Traits;

use Illuminate\Cache\Events\CacheHit;
use Symfony\Component\HttpKernel\CacheClearer\ChainCacheClearer;

trait TokenTrait
{

    /**
     * 生成用户的token，并且存储
     */
    public function createToken( $user_id )
    {

        $user_index = $this->getUserIndex( $user_id );
        $minutes = env( 'TOKEN_DAYS', 7 ) * 60 * 24;
        $token = str_random( 60 );

        //检查之前是否存有token
        $this->delToken( $user_id );

        return \Cache::remember( $user_index, $minutes, function() use ( $user_id, $minutes, $token )
        {
            \Cache::put( $token, $user_id, $minutes + 5 );

            return $token;
        } );
    }

    /**
     * 清除用户token
     */
    public function delToken( $user_id )
    {

        $user_index = $this->getUserIndex( $user_id );

        if( $str_key = \Cache::pull( $user_index ) )
        {
            \Cache::forget( $str_key );
        }
    }

    public function getUserIndex( $user_id )
    {
        return $user_id . '_index';//请考虑user/admin互相窜用token的安全风险，若前后台使用相同的redis服务存储token
    }

    public static function checkToken( $token )
    {
        return \Cache::has( $token );
    }

}