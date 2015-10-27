<?php

namespace boss\models\operation;

use Yii;
/**
 * This is the model class for table "{{%operation_category_type}}".
 *
 * @property integer $id
 * @property string $operation_category_type_name
 * @property integer $operation_category_id
 * @property string $operation_category_name
 * @property string $operation_category_type_introduction
 * @property string $operation_category_type_english_name
 * @property string $operation_category_type_start_time
 * @property string $operation_category_type_end_time
 * @property string $operation_category_type_service_time_slot
 * @property integer $operation_category_type_service_interval_time
 * @property integer $operation_price_strategy_id
 * @property string $operation_price_strategy_name
 * @property string $operation_category_type_price
 * @property string $operation_category_type_balance_price
 * @property string $operation_category_type_additional_cost
 * @property string $operation_category_type_lowest_consume
 * @property string $operation_category_type_price_description
 * @property string $operation_category_type_market_price
 * @property string $operation_tags
 * @property string $operation_category_type_app_ico
 * @property string $operation_category_type_pc_ico
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationCategoryType extends \core\models\operation\OperationCategoryType
{
    /**
     * @inheritdoc
     */
//    public static function tableName()
//    {
//        return '{{%operation_category_type}}';
//    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['operation_category_id', 'operation_category_type_service_interval_time', 'operation_price_strategy_id', 'created_at', 'updated_at'], 'integer'],
            [['operation_category_type_introduction', 'operation_category_type_service_time_slot', 'operation_category_type_price_description', 'operation_tags', 'operation_category_type_app_ico', 'operation_category_type_pc_ico'], 'string'],
            [['operation_category_type_price', 'operation_category_type_balance_price', 'operation_category_type_additional_cost', 'operation_category_type_lowest_consume', 'operation_category_type_market_price'], 'number'],
            [['operation_category_type_name', 'operation_category_type_english_name'], 'string', 'max' => 60],
            [['operation_category_name'], 'string', 'max' => 11],
            [['operation_category_type_start_time', 'operation_category_type_end_time'], 'string', 'max' => 20],
            [['operation_price_strategy_name'], 'string', 'max' => 30],
            [['operation_category_type_name', 'operation_category_type_start_time','operation_category_type_end_time','operation_category_type_service_interval_time','operation_category_type_market_price'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '编号',
            'operation_category_type_name' => '类型名称',
            'operation_category_id' => '上级品类编号',
            'operation_category_name' => '上级品类名称',
            'operation_category_type_introduction' => '服务类型简介',
            'operation_category_type_english_name' => '英文名称',
            'operation_category_type_start_time' => '开始服务时间',
            'operation_category_type_end_time' => '结束服务时间',
            'operation_category_type_service_time_slot' => '可服务时间段',
            'operation_category_type_service_interval_time' => '服务间隔时间',
            'operation_price_strategy_id' => '价格策略编号',
            'operation_price_strategy_name' => '价格策略名称',
            'operation_category_type_price' => '价格',
            'operation_category_type_balance_price' => '阿姨结算价格',
            'operation_category_type_additional_cost' => '附加费用',
            'operation_category_type_lowest_consume' => '最低消费',
            'operation_category_type_price_description' => '价格备注',
            'operation_category_type_market_price' => '市场价格',
            'operation_tags' => '服务类型标签编号',
            'operation_category_type_app_ico' => 'APP端图标',
            'operation_category_type_pc_ico' => 'PC端图标',
            'created_at' => '创建时间',
            'updated_at' => '编辑时间',
        ];
    }
}
