<?php

namespace common\models\operation;

use Yii;

/**
 * This is the model class for table "{{%operation_spec_goods}}".
 *
 * @property integer $id
 * @property integer $operation_goods_id
 * @property string $operation_goods_name
 * @property string $operation_spec_goods_no
 * @property integer $operation_spec_id
 * @property integer $operation_spec_name
 * @property integer $operation_spec_value
 * @property integer $operation_spec_goods_lowest_consume_number
 * @property string $operation_spec_goods_sell_price
 * @property string $operation_spec_goods_market_price
 * @property string $operation_spec_goods_cost_price
 * @property string $operation_spec_goods_settlement_price
 * @property integer $operation_spec_goods_commission_mode
 * @property string $operation_spec_goods_commission
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationSpecGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operation_spec_goods}}';
    }
}
