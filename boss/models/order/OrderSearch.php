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

    /**
     * TODO 获取开通省份列表
     * @return array
     */
    public function getOnlineProvinceList(){
        $province_list = OperationCity::find()->select(['province_id','province_name'])->where(['operation_city_is_online'=>1])->groupBy(['province_id'])->all();
        return ArrayHelper::map($province_list,'province_id','province_name');
    }
}
