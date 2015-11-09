<?php

namespace boss\models\order;

use Yii;
use core\models\order\OrderSearch as CoreOrderSearch;

class OrderSearch extends CoreOrderSearch
{
    /**
     * 获取单个订单
     * @author lin
     * @param $code
     * @return null|static
     */
    public static function getOneByCode($code)
    {
        return Order::findOne(['order_code' => $code]);
    }
}
