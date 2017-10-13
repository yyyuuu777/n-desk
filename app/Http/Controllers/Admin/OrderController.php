<?php
/**
 * Created by PhpStorm.
 * User: like
 * Date: 21/9/2560
 * Time: 11:03 น.
 */

namespace App\Http\Controllers\Admin;

use App\Helper\Traits\BuilderSearchTrait;
use App\Http\Controllers\Controller;
use App\Model\Order;

class OrderController extends Controller
{

    use BuilderSearchTrait;

    public function OrderList()
    {
        $builder = Order::query();
        $orders = $this->search( $builder, $this->request->input() );

        return $this->responseJson( $orders );
    }

}