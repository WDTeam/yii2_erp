<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%customer_worker}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property integer $woker_id
 * @property integer $customer_worker_status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class CustomerWorker extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_worker}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'worker_id', 'customer_worker_status', 'created_at', 'updated_at'], 'required'],
            [['customer_id', 'worker_id', 'customer_worker_status', 'created_at', 'updated_at', 'is_del'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('boss', '主键'),
            'customer_id' => Yii::t('boss', '关联用户'),
            'worker_id' => Yii::t('boss', '关联阿姨'),
            'customer_worker_status' => Yii::t('boss', '阿姨类型1为默认阿姨，-1为非默认阿姨'),
            'created_at' => Yii::t('boss', '创建时间'),
            'updated_at' => Yii::t('boss', '更新时间'),
            'is_del' => Yii::t('boss', '是否逻辑删除'),
        ];
    }
}
