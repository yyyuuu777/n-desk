<?php
/**
 * Created by PhpStorm.
 * User: like
 * Date: 20/9/2560
 * Time: 15:26 น.
 */

namespace App\Http\Controllers\Admin;

use App\Helper\Traits\BuilderSearchTrait;
use App\Http\Controllers\Controller;
use App\Model\Withdraw;

class WithdrawController extends Controller
{

    use BuilderSearchTrait;

    /**
     * 列表
     *
     * @param string $name   账号
     * @param string $email  邮箱
     * @param int    $status 状态
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function WithdrawList()
    {
        $inputs = $this->request->input();

        $builder = new Withdraw();
        $builder = $builder->orderBy( 'id', 'desc' );
        $builder = $builder->whereHas( 'user', function( $query ) use ( $inputs )
        {
            if( isset( $inputs[ 'name' ] ) )
                $query->where( 'name', $inputs[ 'name' ] );
            if( isset( $inputs[ 'email' ] ) )
                $query->where( 'email', $inputs[ 'email' ] );
        } );
        $withdraws = $this->search( $builder, $inputs );

        return $this->responseJson( $withdraws );
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * pass withdraw
     */
    public function WithdrawReq()
    {
        $inputs = $this->request->input();
        if( ( $rs = Withdraw::ColumnValidate( $inputs ) ) !== TRUE )
        {
            return $this->responseJson( $rs, '验证失败', 400 );
        }

        $id = $this->request->input( 'id' );
        $withdraw = Withdraw::find( $id );

        $status = $this->request->input( 'status' );
        $withdraw->status = $status;
        if( $withdraw->save() )
        {
            return $this->responseJson( [], '已通过', 200 );
        } else
        {
            return $this->responseJson( [], '异常', 400 );
        }
    }

}