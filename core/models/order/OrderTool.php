<?php
/**
 * Created by PhpStorm.
 * User: LinHongYou
 * Date: 2015/10/27
 * Time: 19:13
 */

namespace core\models\order;


use Yii;
use yii\base\Model;

class OrderTool extends Model
{

    public static function createOrderCode($prefix=''){
        $num = time()-strtotime(date('Y-m-d 00:00:00'));
        $code = str_pad($num,5,'0',STR_PAD_LEFT);
        $order_code = date("ymd{$code}");
        return $prefix.'00'.$order_code.rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
    }
}