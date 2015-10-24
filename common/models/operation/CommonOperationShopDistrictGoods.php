<?php

namespace common\models\operation;

use Yii;


class CommonOperationShopDistrictGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operation_shop_district_goods}}';
    }
    
}
