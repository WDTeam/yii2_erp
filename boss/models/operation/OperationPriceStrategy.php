<?php

namespace boss\models\operation;

use Yii;
use core\models\operation\OperationPriceStrategy as CoreOperationPriceStrategy;
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
class OperationPriceStrategy extends CoreOperationPriceStrategy
{
    /**
     * @inheritdoc
     */
//    public static function tableName()
//    {
//        return '{{%operation_price_strategy}}';
//    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['created_at', 'updated_at'], 'integer'],
            [['operation_price_strategy_name', 'operation_price_strategy_unit', 'operation_price_strategy_lowest_consume_unit'], 'required']
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '编号',
            'operation_price_strategy_name' => '规格名称',
            'operation_price_strategy_unit' => '价格计量单位',
            'operation_price_strategy_lowest_consume_unit' => '最低消费计量单位',
            'created_at' => '创建时间',
            'updated_at' => '编辑时间',
        ];
    }
    
    /**
     * 
     */
    public static function getAllStrategy()
    {
        $priceStrategies = self::getAll();
        $priceStrategies_array = [0 => '选择商品规格'];
        foreach($priceStrategies as $k => $v){
            $priceStrategies_array[$v->id] = $v->operation_price_strategy_name;
        }
        return $priceStrategies_array;
    }
}
