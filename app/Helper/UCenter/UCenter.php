<?php

namespace App\Helper\UCenter;

class UCenter
{

    private $uid, $key, $host;
    public $err_no;
    public $err_msg;
    public static $UC = NULL;

    public function __construct()
    {
        $this->key = env( 'AUTH_KEY', 'e4a86cfcd723a957eccb48800e3855c6' );//申请新key
        $this->uid = env( 'AUTH_UID', '1499850328' );//申请新uid
        $this->host = env( 'AUTH_USER_URL' );//
    }

    public static function get_UC()
    {
        if( self::$UC == NULL )
        {
            self::$UC = new self();
        }

        return self::$UC;
    }

    /**
     * 用户登录
     * @param array $login_info
     * @return bool
     */
    public function login( array $login_info )
    {
        $url = "a_login";

        return $this->postRequest( $login_info, $url );
    }

    public function register( array $data )
    {
        $url = "a_register";

        return $this->postRequest( $data, $url );
    }

    public function updateEmail( array $data )
    {
        $url = 'a_edit_email';

        return $this->postRequest( $data, $url );
    }

    public function updatePassword( array $data )
    {
        $url = 'a_edit_pass';

        return $this->postRequest( $data, $url );
    }

    //重置密码
    public function resetPassword( array $data )
    {
        $url = 'a_reset_pass';

        return $this->postRequest( $data, $url );
    }

    /**
     * 发起请求
     */
    public function postRequest( array $data, string $url )
    {
        $post_data = $this->postParam( $data );
        $curl = curl_init();
        $opt = [
            CURLOPT_URL => $this->host . '/' . $url,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_FOLLOWLOCATION => TRUE,
            CURLOPT_POST => TRUE,
            CURLOPT_POSTFIELDS => $post_data,
            CURLOPT_HEADER => FALSE,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.86 Safari/537.36',

        ];
        curl_setopt_array( $curl, $opt );
        $result = curl_exec( $curl );
        $err = curl_errno( $curl );
        $err_msg = curl_error( $curl );
        curl_close( $curl );
        if( $err > 0 )
        {
            $this->err_msg = $err_msg;
            $this->err_no = $err;
        }

        return json_decode( $result, TRUE );
    }

    public function postParam( $data )
    {
        $data[ 'uid' ] = $this->uid;
        $data[ 'sign' ] = $this->getSign( $data );

        return $data;
    }

    public function getSign( $data )
    {
        ksort( $data );
        $str = '';
        foreach( $data as $key => $value )
        {
            $str .= $key . '=' . $value . '&';
        }

        return md5( $str . $this->key );
    }

}