<?php

namespace core\models\customer;

use Yii;

/**
 * This is the model class for table "{{%customer_block_log}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property integer $customer_block_log_status
 * @property string $customer_block_log_reason
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class CustomerBlockLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_block_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'customer_block_log_status', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['created_at', 'updated_at'], 'required'],
            [['customer_block_log_reason'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('boss', '编号'),
            'customer_id' => Yii::t('boss', '客户'),
            'customer_block_log_status' => Yii::t('boss', '状态，1为黑名单0为正常'),
            'customer_block_log_reason' => Yii::t('boss', '原因'),
            'created_at' => Yii::t('boss', '创建时间'),
            'updated_at' => Yii::t('boss', '更新时间'),
            'is_del' => Yii::t('boss', '是否逻辑删除'),
        ];
    }
}
