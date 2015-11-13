<?php

namespace boss\models\operation;

use Yii;

/**
 * This is the model class for table "ejj_operation_category".
 *
 * @property integer $id
 * @property string $operation_category_name
 * @property string $operation_category_icon
 * @property string $operation_category_introduction
 * @property string $operation_category_price_description
 * @property string $operation_category_url
 * @property integer $operation_category_parent_id
 * @property string $operation_category_parent_name
 * @property integer $sort
 * @property integer $is_softdel
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationCategory extends \core\models\operation\OperationCategory
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operation_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['operation_category_name'], 'string', 'max' => 30],
            ['operation_category_name', 'unique', 'message' => '该服务品类已存在'],
            [['operation_category_name', 'operation_category_introduction'], 'required'],
            [['operation_category_introduction'], 'string'],
            [['operation_category_parent_id', 'sort', 'is_softdel', 'created_at', 'updated_at'], 'integer'],
            [['operation_category_icon', 'operation_category_price_description'], 'string', 'max' => 128],
            [['operation_category_url'], 'string', 'max' => 258],
            ['operation_category_icon', 'file', 'extensions' => ['png', 'jpg', 'gif'], 'maxSize' => 1024*1024*1024],
            ['operation_category_icon', 'required', 'on' => ['create']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '编号'),
            'operation_category_name' => Yii::t('app', '服务品类名称'),
            'operation_category_icon' => Yii::t('app', '服务品类图片'),
            'operation_category_introduction' => Yii::t('app', '服务品类简介'),
            'operation_category_price_description' => Yii::t('app', '价格备注'),
            'operation_category_url' => Yii::t('app', '品类跳转地址'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '编辑时间'),


            'operation_category_app_ico' => Yii::t('app', 'APP端图标(序列化方式存储|首页大图，首页小图，分类页小图，订单页小图)'),
            'operation_category_app_homepage_max_ico' => Yii::t('app', 'APP端首页大图'),
            'operation_category_app_homepage_min_ico' => Yii::t('app', 'APP端首页小图'),
            'operation_category_app_type_min_ico' => Yii::t('app', 'APP端分类页小图'),
            'operation_category_app_order_min_ico' => Yii::t('app', 'APP端订单页小图'),

            'operation_category_pc_ico' => Yii::t('app', 'PC端图标(序列化方式存储|首页推荐大图，更多推荐大图，下单页小图)'),
            'operation_category_pc_homepage_max_ico' => Yii::t('app', 'PC端首页推荐大图'),
            'operation_category_pc_more_max_ico' => Yii::t('app', 'PC端更多推荐大图'),
            'operation_category_pc_submit_order_min_ico' => Yii::t('app', 'PC端下单页小图'),
        ];
    }
}
