<?php
/**
 * Created by PhpStorm.
 * User: LinHongYou
 * Date: 2015/11/2
 * Time: 14:14
 */
namespace core\models\order;

use Yii;
use dbbase\models\order\OrderOtherDict as OrderOtherDictModel;
use yii\helpers\ArrayHelper;

class OrderOtherDict extends OrderOtherDictModel
{

    public static function getCancelOrderCauseType()
    {
        return [self::TYPE_CANCEL_ORDER_CUSTOMER_CAUSE=>'客户原因',self::TYPE_CANCEL_ORDER_COMPANY_CAUSE=>'公司原因'];
    }

    public static function getCancelOrderCustomerCause()
    {
        $result = self::find()->where(['order_other_dict_type'=>self::TYPE_CANCEL_ORDER_CUSTOMER_CAUSE])->orderBy(['id'=>SORT_DESC])->all();
        return ArrayHelper::map($result, 'id', 'order_other_dict_name');
    }

    public static function getCancelOrderCompanyCause()
    {
        $result = self::find()->where(['order_other_dict_type'=>self::TYPE_CANCEL_ORDER_COMPANY_CAUSE])->orderBy(['id'=>SORT_DESC])->all();
        return ArrayHelper::map($result, 'id', 'order_other_dict_name');
    }
}