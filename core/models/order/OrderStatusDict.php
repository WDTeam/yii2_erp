<?php
/**
 * Created by PhpStorm.
 * User: LinHongYou
 * Date: 2015/11/2
 * Time: 14:14
 */
namespace core\models\order;

use Yii;
use dbbase\models\order\OrderStatusDict as OrderStatusDictModel;
use yii\helpers\ArrayHelper;

class OrderStatusDict extends OrderStatusDictModel
{

    public static function getBossStatusDictList($status_id)
    {
        $list = self::find()->where(['not in','id',[self::ORDER_CANCEL,self::ORDER_DIED]])->andWhere(['>','id',$status_id])->orderBy(['id'=>SORT_ASC])->all();
        return ArrayHelper::map($list, 'id', 'order_status_boss');
    }

}