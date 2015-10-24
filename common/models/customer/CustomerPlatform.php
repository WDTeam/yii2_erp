<?php

namespace common\models\customer;

use Yii;

/**
 * This is the model class for table "{{%customer_platform}}".
 *
 * @property integer $id
 * @property string $platform_name
 * @property string $platform_ename
 * @property integer $pid
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class CustomerPlatform extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_platform}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['platform_name', 'platform_ename', 'created_at', 'updated_at'], 'required'],
            [['pid', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['platform_name', 'platform_ename'], 'string', 'max' => 16]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('boss', '主键'),
            'platform_name' => Yii::t('boss', '平台名称'),
            'platform_ename' => Yii::t('boss', '平台拼音'),
            'pid' => Yii::t('boss', '父级id'),
            'created_at' => Yii::t('boss', '创建时间'),
            'updated_at' => Yii::t('boss', '更新时间'),
            'is_del' => Yii::t('boss', '是否逻辑删除'),
        ];
    }
}
