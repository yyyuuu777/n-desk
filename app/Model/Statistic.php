<?php
/**
 * Created by PhpStorm.
 * User: like
 * Date: 20/9/2560
 * Time: 15:25 น.
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\File;
use App\Model\Order;
use App\Model\User;
use App\Model\Withdraw;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helper\Traits\BuilderSearchTrait;

class Statistic extends Model
{

    use BuilderSearchTrait;

    protected $guarded = [];

    const TYPE_DAILY = 1;
    const TYPE_TOTAL = 2;

    public function SizeHuman()
    {
        $this->file_size_human = $this->Size2Human( $this->file_size );
        $this->file_download_size_human = $this->Size2Human( $this->file_download_size );
    }

    /**
     * 统计
     */
    public function Statistic( $date, $type )
    {

        $this->date = $date;
        $this->type = $type;

        $file = new File();
        $user = new User();
        $order = new Order();
        $withdraw = new Withdraw();

        //实时数据
        $this->file_download_num = $file->sum( 'downloads' );//实时总量；没有昨日量，因为没有下载记录表
        $this->file_download_size = $file->getDownloadSize();

        //类型
        switch( $type )
        {
            case self::TYPE_DAILY:
                $user = $user->whereDate( 'created_at', '=', $date );//用户，注册就算
                $order = $order->whereDate( 'updated_at', '=', $date );//支付，给钱才算，updated
                $withdraw = $withdraw->whereDate( 'updated_at', '=', $date );//审核，通过才算，updated
                $file = $file->whereDate( 'created_at', '=', $date );//文件，存了就算
                break;
            case self::TYPE_TOTAL:
                $user = $user->whereDate( 'created_at', '<=', $date );//用户，注册时就算
                $order = $order->whereDate( 'updated_at', '<=', $date );//支付，给钱时才算，updated
                $withdraw = $withdraw->whereDate( 'updated_at', '<=', $date );//审核，通过时才算，updated
                $file = $file->whereDate( 'created_at', '<=', $date );//文件，存时就算
                break;
        }

        //昨日数据
        $this->user_num = $user->count( 'id' );//用户数
        $this->money = $order->where( 'status', Order::STATUS_DONE )->sum( 'price' );//支付金额
        $this->withdraw_num = $withdraw->where( 'status', Withdraw::STATUS_PASS )->count( 'id' );//提现数
        $this->withdraw_money = $withdraw->where( 'status', Withdraw::STATUS_PASS )->sum( 'money' );//提现金额
        $this->file_num = $file->count( 'id' );//文件量
        $this->file_size = $file->sum( 'size' );//文件大小
    }

}