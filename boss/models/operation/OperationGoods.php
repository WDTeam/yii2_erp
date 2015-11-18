<?php

namespace boss\models\operation;

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
 * @property string operation_goods_pc_ico
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationGoods extends \core\models\operation\OperationGoods
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['operation_category_id'], 'required', 'message' => '请选择服务类型'],
            [['operation_spec_info'], 'required', 'message' => '商品规格不能为空'],
            [[
                'operation_goods_english_name',
                'operation_goods_introduction',
                'operation_goods_name',
                'operation_goods_price_description',
            ], 'required'],
            ['operation_goods_english_name', 'match', 'pattern' => '/^[a-zA-Z]|[\s]\w*$/i', 'message' => '只能输入英文'],
            [['operation_category_id', 'operation_goods_service_interval_time', 'operation_goods_service_estimate_time', 'created_at', 'updated_at'], 'integer'],
            [['operation_goods_introduction', 'operation_goods_service_time_slot', 'operation_goods_price_description', 'operation_tags', 'operation_goods_app_ico', 'operation_goods_pc_ico'], 'string'],
            [['operation_goods_additional_cost', 'operation_goods_market_price'], 'number'],
            [['operation_goods_name', 'operation_category_name', 'operation_goods_english_name'], 'string', 'max' => 60],
            [['operation_category_ids'], 'string', 'max' => 100],
            [['operation_goods_start_time', 'operation_goods_end_time'], 'string', 'max' => 20],
            ['operation_goods_img', 'file', 'extensions' => ['png', 'jpg', 'gif'], 'maxSize' => 1024*1024*1024],
            ['operation_goods_img', 'required', 'on' => ['create']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '编号'),
            'operation_goods_name' => Yii::t('app', '服务项目名称'),

            'operation_category_id' => Yii::t('app', '对应服务品类编号（所属分类编号冗余）'),
            'operation_category_ids' => Yii::t('app', '服务品类'),
            'operation_category_name' => Yii::t('app', '服务品类名称'),
            'operation_goods_introduction' => Yii::t('app', '服务项目简介'),
            'operation_goods_english_name' => Yii::t('app', '项目英文名称'),
            'operation_goods_start_time' => Yii::t('app', '开始服务时间'),
            'operation_goods_end_time' => Yii::t('app', '结束服务时间'),
            'operation_goods_service_time_slot' => Yii::t('app', '可服务时间段（序列化方式存储）'),
            'operation_goods_service_interval_time' => Yii::t('app', '服务间隔时间(单位：分钟）'),
            'operation_goods_service_estimate_time' => Yii::t('app', '预计服务时长(单位：分钟)'),

//            'operation_price_strategy_id' => Yii::t('app', '价格策略编号'),
//            'operation_price_strategy_name' => Yii::t('app', '价格策略名称'),
            'operation_goods_price' => Yii::t('app', '售价(元)'),
            'operation_goods_balance_price' => Yii::t('app', '结算价格(元)'),

            'operation_goods_additional_cost' => Yii::t('app', '附加费用'),
            'operation_goods_lowest_consume' => Yii::t('app', '最低消费(元)'),
            'operation_goods_price_description' => Yii::t('app', '价格备注'),
            'operation_goods_market_price' => Yii::t('app', '市场价格(元)'),
            'operation_tags' => Yii::t('app', '个性标签'),

            'operation_goods_img' => Yii::t('app', '服务项目图片'),
            'operation_goods_app_ico' => Yii::t('app', 'APP端图标(序列化方式存储|首页大图，首页小图，分类页小图，订单页小图)'),
            'operation_goods_app_homepage_max_ico' => Yii::t('app', 'APP端首页大图'),
            'operation_goods_app_homepage_min_ico' => Yii::t('app', 'APP端首页小图'),
            'operation_goods_app_type_min_ico' => Yii::t('app', 'APP端分类页小图'),
            'operation_goods_app_order_min_ico' => Yii::t('app', 'APP端订单页小图'),

            'operation_goods_pc_ico' => Yii::t('app', 'PC端图标(序列化方式存储|首页推荐大图，更多推荐大图，下单页小图)'),
            'operation_goods_pc_homepage_max_ico' => Yii::t('app', 'PC端首页推荐大图'),
            'operation_goods_pc_more_max_ico' => Yii::t('app', 'PC端更多推荐大图'),
            'operation_goods_pc_submit_order_min_ico' => Yii::t('app', 'PC端下单页小图'),

            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '编辑时间'),
        ];
    }
}
