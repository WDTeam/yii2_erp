<?php

namespace dbbase\models\operation;

use Yii;
/**
 * This is the model class for table "{{%operation_goods}}".
 *
 * @property integer $id
 * @property string $operation_goods_name
 * @property integer $operation_category_id
 * @property string $operation_category_ids
 * @property string $operation_category_name
 * @property string $operation_goods_introduction
 * @property string $operation_goods_english_name
 * @property string $operation_goods_start_time
 * @property string $operation_goods_end_time
 * @property string $operation_goods_service_time_slot
 * @property integer $operation_goods_service_interval_time
 * @property integer $operation_price_strategy_id
 * @property string $operation_price_strategy_name
 * @property string $operation_goods_price
 * @property string $operation_goods_balance_price
 * @property string $operation_goods_additional_cost
 * @property string $operation_goods_lowest_consume
 * @property string $operation_goods_price_description
 * @property string $operation_goods_market_price
 * @property string $operation_tags
 * @property string $operation_goods_app_ico
 * @property string $operation_goods_type_pc_ico
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operation_goods}}';
    }
}
