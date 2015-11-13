<?php

namespace boss\models\operation;

use Yii;
/**
 * This is the model class for table "{{%operation_shop_district_goods}}".
 *
 * @property integer $id
 * @property string $operation_shop_district_goods_name
 * @property string $operation_shop_district_goods_no
 * @property integer $operation_goods_id
 * @property integer $operation_shop_district_id
 * @property string $operation_shop_district_name
 * @property integer $operation_city_id
 * @property string $operation_city_name
 * @property integer $operation_category_id
 * @property string $operation_category_ids
 * @property string $operation_category_name
 * @property string $operation_shop_district_goods_introduction
 * @property string $operation_shop_district_goods_english_name
 * @property string $operation_shop_district_goods_start_time
 * @property string $operation_shop_district_goods_end_time
 * @property string $operation_shop_district_goods_service_time_slot
 * @property integer $operation_shop_district_goods_service_interval_time
 * @property integer $operation_shop_district_goods_service_estimate_time
 * @property string $operation_spec_info
 * @property string $operation_spec_strategy_unit
 * @property string $operation_shop_district_goods_price
 * @property string $operation_shop_district_goods_balance_price
 * @property string $operation_shop_district_goods_additional_cost
 * @property integer $operation_shop_district_goods_lowest_consume_num
 * @property string $operation_shop_district_goods_lowest_consume
 * @property string $operation_shop_district_goods_price_description
 * @property string $operation_shop_district_goods_market_price
 * @property string $operation_tags
 * @property string $operation_goods_img
 * @property string $operation_shop_district_goods_app_ico
 * @property string $operation_shop_district_goods_pc_ico
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationShopDistrictGoods extends \core\models\operation\OperationShopDistrictGoods
{

    /**
     * 对应城市下上线服务项目的商圈数量
     */
	public $district_nums;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['operation_goods_id', 'operation_shop_district_id', 'operation_city_id', 'operation_category_id', 'operation_shop_district_goods_service_interval_time', 'operation_shop_district_goods_service_estimate_time', 'operation_shop_district_goods_lowest_consume_num', 'created_at', 'updated_at'], 'integer'],
            [['operation_shop_district_goods_introduction', 'operation_shop_district_goods_service_time_slot', 'operation_spec_info', 'operation_shop_district_goods_price_description', 'operation_tags', 'operation_goods_img', 'operation_shop_district_goods_app_ico', 'operation_shop_district_goods_pc_ico'], 'string'],
            [['operation_shop_district_goods_price', 'operation_shop_district_goods_balance_price', 'operation_shop_district_goods_additional_cost', 'operation_shop_district_goods_lowest_consume', 'operation_shop_district_goods_market_price'], 'number'],
            [['operation_shop_district_goods_name', 'operation_shop_district_name', 'operation_category_name', 'operation_shop_district_goods_english_name', 'operation_spec_strategy_unit'], 'string', 'max' => 60],
            [['operation_shop_district_goods_no', 'operation_shop_district_goods_start_time', 'operation_shop_district_goods_end_time'], 'string', 'max' => 20],
            [['operation_city_name'], 'string', 'max' => 50],
            [['operation_category_ids'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('operation', '编号'),
            'operation_shop_district_goods_name' => Yii::t('operation', '服务项目名称'),
            'operation_shop_district_goods_no' => Yii::t('operation', '商品货号'),
            'operation_goods_id' => Yii::t('operation', '商品编号'),
            'operation_shop_district_id' => Yii::t('operation', '商圈id'),
            'operation_shop_district_name' => Yii::t('operation', '商圈名称'),
            'operation_city_id' => Yii::t('operation', '城市编号'),
            'operation_city_name' => Yii::t('operation', '城市名称'),
            'district_nums' => Yii::t('operation', '商圈数量'),
            'operation_category_id' => Yii::t('operation', '对应服务品类编号（所属分类编号冗余）'),
            'operation_category_ids' => Yii::t('operation', '对应服务品类的所有编号以“,”关联'),
            'operation_category_name' => Yii::t('operation', '服务品类名称'),
            'operation_shop_district_goods_introduction' => Yii::t('operation', '服务类型简介'),
            'operation_shop_district_goods_english_name' => Yii::t('operation', '服务类型英文名称'),
            'operation_shop_district_goods_start_time' => Yii::t('operation', '开始服务时间即开始时间'),
            'operation_shop_district_goods_end_time' => Yii::t('operation', '结束服务时间即结束时间'),
            'operation_shop_district_goods_service_time_slot' => Yii::t('operation', '可服务时间段（序列化方式存储）'),
            'operation_shop_district_goods_service_interval_time' => Yii::t('operation', '服务间隔时间(单位：分钟)'),
            'operation_shop_district_goods_service_estimate_time' => Yii::t('operation', '预计服务时长(单位：分钟)'),
            'operation_spec_info' => Yii::t('operation', '序列化存储规格'),
            'operation_spec_strategy_unit' => Yii::t('operation', '计量单位'),
            'operation_shop_district_goods_price' => Yii::t('operation', '售价'),
            'operation_shop_district_goods_balance_price' => Yii::t('operation', '阿姨结算价格'),
            'operation_shop_district_goods_additional_cost' => Yii::t('operation', '附加费用'),
            'operation_shop_district_goods_lowest_consume_num' => Yii::t('operation', '最低消费数量'),
            'operation_shop_district_goods_lowest_consume' => Yii::t('operation', '最低消费价格'),
            'operation_shop_district_goods_price_description' => Yii::t('operation', '价格备注'),
            'operation_shop_district_goods_market_price' => Yii::t('operation', '市场价格'),
            'operation_tags' => Yii::t('operation', '服务类型标签编号(序列化方式存储)'),
            'operation_goods_img' => Yii::t('operation', '商品图片'),
            'operation_shop_district_goods_app_ico' => Yii::t('operation', 'APP端图标(序列化方式存储|首页大图，首页小图，分类页小图，订单页小图)'),
            'operation_shop_district_goods_pc_ico' => Yii::t('operation', 'PC端图标(序列化方式存储|首页推荐大图，更多推荐大图，下单页小图)'),
            'created_at' => Yii::t('operation', '创建时间'),
            'updated_at' => Yii::t('operation', '编辑时间'),
        ];
    }
}
