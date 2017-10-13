<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{

    public $request = NULL;

    const SUCCESS = 200;//成功 operation success
    const FAIL = 400;//失败 operation no throwable client operation forbidden
    const BAD_REQUEST = 400;//请求错误 Bad request , server can't understand client operation
    const VALIDATE_FAIL = 400;//参数错误 client pass data wrong
    const RESOURCE_FAIL = 400;//资源错误 client pass data wrong
    const AUTHORIZED_FAIL = 401;//权限错误 Unauthorized access, not through the authority of the certification
    const SERVER_ERROR = 500;//服务器错误 serve error

    public function __construct( Request $request )
    {
        $this->request = $request;
        $this->inputs = $request->input();
    }

    public function responseJson( $data = [], $msg = '执行成功', $status = 200 )
    {
        if( !in_array( $status, [ 200, 300, 400, 401, 404, 500 ] ) )
        {
            $status = 500;
            $msg = '程序内部错误';
        }

        $response = [
            'status' => $status,
            'msg' => $msg
        ];

        if( !empty( $data ) )
        {
            $response[ 'data' ] = $data;
        }

        return response()->json( $response );
    }

    //验证-格式化返回
    public function validateJson( $inputs, $rules, $messages )
    {

        $validation = \Validator::make( $inputs, $rules, $messages );

        if( $validation->fails() )
        {
            $msg = $validation->errors()->toArray();
            foreach( $msg as $key => $value )
            {
                return $this->responseJson( [], $value[ 0 ], 400 );//有错误
            }
        }

        return FALSE;//没有错误
    }

}
