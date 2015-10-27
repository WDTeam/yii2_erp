<?php

namespace boss\models\operation;

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
class OperationSpecGoods extends \core\models\operation\OperationSpecGoods
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['operation_goods_id', 'operation_spec_id', 'operation_spec_name', 'operation_spec_value', 'operation_spec_goods_lowest_consume_number', 'operation_spec_goods_commission_mode', 'created_at', 'updated_at'], 'integer'],
            [['operation_spec_goods_sell_price', 'operation_spec_goods_market_price', 'operation_spec_goods_cost_price', 'operation_spec_goods_settlement_price', 'operation_spec_goods_commission'], 'number'],
            [['operation_goods_name', 'operation_spec_strategy_unit', 'operation_spec_strategy_unit'], 'string', 'max' => 60],
            [['operation_spec_goods_no'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '编号'),
            'operation_goods_id' => Yii::t('app', '商品编号'),
            'operation_goods_name' => Yii::t('app', '商品名称'),
            'operation_spec_goods_no' => Yii::t('app', '商品规格货号'),
            'operation_spec_id' => Yii::t('app', '规格编号'),
            'operation_spec_name' => Yii::t('app', '规格名称'),
            'operation_spec_value' => Yii::t('app', '规格属性'),
            'operation_spec_goods_lowest_consume_number' => Yii::t('app', '最低消费数量'),
            'operation_spec_strategy_unit' => Yii::t('app', '计量单位'),
            'operation_spec_goods_sell_price' => Yii::t('app', '商品销售价格'),
            'operation_spec_goods_market_price' => Yii::t('app', '商品市场价格'),
            'operation_spec_goods_cost_price' => Yii::t('app', '商品成本价格'),
            'operation_spec_goods_settlement_price' => Yii::t('app', '商品结算价格'),
            'operation_spec_goods_commission_mode' => Yii::t('app', '收取佣金方式（1: 百分比 2: 金额）'),
            'operation_spec_goods_commission' => Yii::t('app', '佣金值'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '编辑时间'),
        ];
    }
}
