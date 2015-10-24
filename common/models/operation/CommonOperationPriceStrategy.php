<?php

namespace common\models\operation;

use Yii;

/**
 * This is the model class for table "{{%operation_price_strategy}}".
 *
 * @property integer $id
 * @property string $operation_price_strategy_name
 * @property string $operation_price_strategy_unit
 * @property string $operation_price_strategy_lowest_consume_unit
 * @property integer $created_at
 * @property integer $updated_at
 */
class CommonOperationPriceStrategy extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operation_price_strategy}}';
    }
    
    /**
     * 
     */
    public static function getAll()
    {
        $priceStrategies = CommonOperationPriceStrategy::find()->all();
        return $priceStrategies;
    }
}
