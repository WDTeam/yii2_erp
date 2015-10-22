<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%customer_comment_tag}}".
 *
 * @property integer $id
 * @property string $customer_comment_tag_name
 * @property integer $customer_comment_level
 * @property integer $is_online
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class CustomerCommentTag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_comment_tag}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_comment_tag_name'], 'required'],
            [['customer_comment_level', 'is_online', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['customer_comment_tag_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'customer_comment_tag_name' => Yii::t('common', '评价标签名称'),
            'customer_comment_level' => Yii::t('common', '评价等级'),
            'is_online' => Yii::t('common', '是否上线'),
            'created_at' => Yii::t('common', '创建时间'),
            'updated_at' => Yii::t('common', '更新时间'),
            'is_del' => Yii::t('common', '删除'),
        ];
    }
}
