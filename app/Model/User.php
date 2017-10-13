<?php

namespace App\Model;

use App\Helper\AppException;
use App\Helper\UCenter\UCenter;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use SoftDeletes;//软删除

    const STATUS_USER_ACTIVE = 1;
    const STATUS_USER_FROZEN = 0;

    const FILE_PERMISSION_SELF = 3;

    protected $guard = [];

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email', ];

    /**
     * The attributes excluded from the model's JSON form.
     */
    protected $hidden = [
        'password', ];

    /**
     * @des user controller validate
     * @param   string $scene 验证场景
     * @param int      $id    用户ID
     * @return mixed
     */
    public static function rules( $scene, $id = NULL )
    {
        $rules = [
            'login' => [
                'name' => 'required_without:email|between:1,255|exists:users,name',
                'email' => 'required_without:name|email|between:1,255|exists:users,email',
                'password' => 'required|between:6,20', ],
            'register' => [
                'email' => 'required|email|between:1,255|unique:users,email',
                'password' => 'required|confirmed|between:6,20', ],
            'update' => [
                'file_permission' => 'required|integer', ],
            'updateName' => [
                'name' => 'required|between:1,255|unique:users,name,' . $id, ],
            'updateEmail' => [
                'email' => 'required|email|between:1,255|unique:users,email,' . $id, ],
            'updateHead' => [
                'head' => 'required|image|max:10485760|dimensions:max_width=600,max_width=600', ],
            'updatePassword' => [
                'password' => 'required|between:6,20|confirmed',
                'password_old' => 'required|between:6,20', ], ];

        return $rules[ $scene ];
    }

    public static function msg()
    {
        return [
            'id.required' => ':attribute必须传入',
            'id.exists' => ':attribute不存在',
            'name.required' => ':attribute必须传入',
            'name.required_without' => ':attribute必须传入，当没有传入:values时',
            'name.between' => ':attribute长度必须介于:min到:max之间',
            'name.exists' => '该:attribute不存在',
            'name.unique' => '该:attribute已注册',
            'email.required' => ':attribute不可为空',
            'email.required_without' => ':attribute必须传入，当没有传入:values时',
            'email.email' => ':attribute格式不正确',
            'email.exists' => ':attribute不存在',
            'email.between' => ':attribute长度必须介于:min到:max之间',
            'email.unique' => '该:attribute已被注册',
            'password.required' => ':attribute必须传入',
            'password.between' => ':attribute长度必须介于:min到:max之间',
            'password.confirmed' => ':attribute两次输入必须一致',
            'password_old.required' => ':attribute必须传入',
            'password_old.between' => ':attribute长度必须介于:min到:max之间',
            'file_permission.required' => ':attribute必须传入',
            'file_permission.integer' => ':attribute必须是整数',
            'head.required' => ':attribute必须传入',
            'head.image' => ':attribute必须是合法的图像文件',
            'head.dimensions' => ':attribute像素大小不能超过600×600', ];
    }

    /**
     * 冻结/解冻：当用户冻结，解除冻结状态，反之亦然
     * @return bool
     */
    public function FrozenOrUnfrozen()
    {
        $this->status = $this->status == 1 ? 0 : 1;

        return $this->save();
    }

    //转换密码
    public static function getPassword( $str )
    {
        return sha1( md5( $str ) );
    }

    //验证密码
    public function checkPassword( $password )
    {
        return $this->password == self::getPassword( trim( $password ) );
    }

    /**
     * 请求用户中心
     * @return bool
     */
    public function UCenter( $api, $param = [] )
    {
        //用户中心
        try
        {
            $UC = UCenter::get_UC();
            $result = $UC->$api( $param );
        }
        catch( \Exception $e )
        {
            \Log::error( 'UCenter error: ' . $api . '. Error:' . $e );//此时没有返回值

            return FALSE;
        }

        if( !isset( $result ) || $result[ 'Result' ] )
        {
            \Log::info( 'UCenter fail: ' . $api . '. Result:' . $result );

            return FALSE;
        }

        return $result[ 'Data' ][ 'user_id' ] ?? TRUE;
    }

}
