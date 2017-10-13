<?php

namespace App\Http\Controllers\Portal;

use App\Helper\Traits\TokenTrait;
use App\Http\Controllers\Controller;
use App\Model\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\Factory;
/**
 * Created by PhpStorm.
 * User: jorah
 * Date: 10/3/2017 AD
 * Time: 10:03
 */
class UserController extends Controller
{

    use TokenTrait;

    /**
     * 登录 login
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $inputs = $this->request->input();
        if( $error = $this->validateJson( $inputs, User::rules( 'login' ), User::msg() ) )
            return $error;//参数验证

        $key = $this->request->has( 'name' ) ? 'name' : 'email';
        $value = $this->request->input( $key );

        $user = new User();
        $user = $user->where( $key, $value )->first();

        if( $user->status != User::STATUS_USER_ACTIVE )
            return $this->responseJson( [], '该用户已冻结', 400 );

        //密码： check password not match forbidden login
        if( !$user->checkPassword( $inputs[ 'password' ] ) )
            return $this->responseJson( [], '用户密码错误', 400 );

        //用户中心
        if( !$user->UCenter( 'login', [
            'username' => $value,
            'password' => $inputs[ 'password' ] ] )
        )
            return $this->responseJson( [], '用户中心请求失败', 400 );

        return $this->responseJson( [
            'token' => $this->createToken( $user->id ),
            'id' => $user->id, ] );
    }

    /**
     * 退出 logout
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->delToken( $this->request->user_id );

        return $this->responseJson( [], '退出成功' );
    }

    /**
     * 注册 register
     * @param string $email                 邮箱
     * @param string $password              密码
     * @param string $password_confirmation 密码确认
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function register()
    {
        $inputs = $this->request->input();

        if( $error = $this->validateJson( $inputs, User::rules( 'register' ), User::msg() ) )
            return $error;

        $user = new User();

        //用户中心
        $uid = $user->UCenter( 'register', [
            'username' => $inputs[ 'email' ],//产品设计要求：注册时，库内用户名为null，用户自行修改
            'email' => $inputs[ 'email' ],
            'password' => $inputs[ 'password' ] ] );
        if( !$uid )
            return $this->responseJson( [], '用户中心请求失败', 400 );

        $user->uid = $uid;
        $user->email = $inputs[ 'email' ];
        $user->password = $user->getPassword( $inputs[ 'password' ] );
        $user->save();

        $token = $this->createToken( $user->id );

        return $this->responseJson( [
            'token' => $token ], '注册成功' );
    }

    /**
     * 修改 update user info
     * @param int $file_permission 文件权限
     * @return \Illuminate\Http\JsonResponse
     */
    public function update()
    {
        $inputs = $this->request->input();
        $id = $this->request->user_id;

        $user = new User();
        if( $user = $user->find( $id ) )
        {
            if( $error = $this->validateJson( $inputs, User::rules( 'update', $id ), User::msg() ) )
                return $error;
        }
        else
            return $this->responseJson( [], '该用户不存在', 400 );

        $user->file_permission = $inputs[ 'file_permission' ];
        $user->save();

        return $this->responseJson( [], '修改成功' );
    }

    /**
     * 修改用户名
     * @param string $name 名称
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateName()
    {
        $id = $this->request->user_id;
        $user = new User();
        if( !$user = $user->find( $id ) )
            return $this->responseJson( [], '该用户不存在', 400 );

        //用户中心

        $inputs = $this->request->input();
        if( $error = $this->validateJson( $inputs, User::rules( 'updateName', $id ), User::msg() ) )
            return $error;

        if( $user->UpdateName( $inputs ) )
            return $this->responseJson( [], '修改成功', 200 );
        else
            return $this->responseJson( [], '修改失败', 400 );
    }

    /**
     * 修改邮箱
     * @param string $email 邮箱
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateEmail()
    {
        $id = $this->request->user_id;
        $user = new User();
        if( !$user = $user->find( $id ) )
            return $this->responseJson( [], '该用户不存在', 400 );

        $inputs = $this->request->input();
        if( $error = $this->validateJson( $inputs, User::rules( 'updateEmail', $id ), User::msg() ) )
            return $error;

        //用户中心
        if( !$user->UCenter( 'updateEmail', [
            'email' => $inputs[ 'email' ],
            'user_id' => $user->uid, ] )
        )
            return $this->responseJson( [], '用户中心请求失败', 400 );

        $user->email = $inputs[ 'email' ];
        if( $user->save() )
            return $this->responseJson( [], '修改成功', 200 );
        else
            return $this->responseJson( [], '修改失败', 400 );
    }

    /**
     * 修改头像
     * @param string $head 头像（图像文件）
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateHead()
    {
        $id = $this->request->user_id;
        $user = new User();
        if( !$user = $user->find( $id ) )
            return $this->responseJson( [], '该用户不存在', 400 );

        //验证
        $files = $this->request->allFiles();
        if( $error = $this->validateJson( $files, User::rules( 'updateHead' ), User::msg() ) )
            return $error;

        //图像
        $path = getenv( 'IMG_SERVER_PATH' );//本地存储；若使用图片服务器，请改写
        if( !isset( $path ) )
            return $this->responseJson( [], '图像服务暂时不能使用', 400 );

        $head = $this->request->file( 'head' );
        $file_name = md5( time() . rand( 0, 10000 ) ) . '.' . $head->getClientOriginalExtension();//图像名称：md5(时间戳+随机)+后缀
        $path_real = $head->move( $path, $file_name );
        //$path_real = Storage::disk('head')->putFileAs(  '', $head, $file_name  );

        //数据
        $user->head = $this->request->root() . '/' . $path_real;
        if( $user->save() )
            return $this->responseJson( [], '修改成功', 200 );
        else
            return $this->responseJson( [], '修改失败', 400 );
    }

    /**
     * 修改密码
     * @param string $password              密码
     * @param string $password_confirmation 密码确认
     * @param string $password_old          旧密码
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword()
    {
        $inputs = $this->request->input();
        $id = $this->request->user_id;

        if( $error = $this->validateJson( $inputs, User::rules( 'updatePassword' ), User::msg() ) )
            return $error;

        $user = new User();
        if( !$user = $user->find( $id ) )
            return $this->responseJson( [], '该用户不存在', 400 );

        if( !$user->checkPassword( $inputs[ 'password_old' ] ) )
            return $this->responseJson( [], '旧密码错误', 400 );

        //用户中心
        if( !$user->UCenter( 'updatePassword', [
            'new_password' => $inputs[ 'password' ],
            'old_password' => $inputs[ 'password_old' ],
            'user_id' => $user->uid, ] )
        )
            return $this->responseJson( [], '用户中心请求失败', 400 );

        $user->password = User::getPassword( $inputs[ 'password' ] );
        if( $user->save() )
            return $this->responseJson( [], '密码修改成功', 200 );
        else
            return $this->responseJson( [], '密码修改失败', 400 );
    }

    //重置密码：发送邮件？

    //绑定邮箱

}
