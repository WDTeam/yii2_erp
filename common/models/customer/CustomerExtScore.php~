<?php

namespace common\models\customer;

use Yii;

/**
 * This is the model class for table "{{%customer_ext_score}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property integer $customer_balance
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class CustomerExtScore extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_ext_score}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'customer_score', 'created_at', 'updated_at', 'is_del'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('boss', 'ID'),
            'customer_id' => Yii::t('boss', '客户'),
            'customer_score' => Yii::t('boss', '客户积分'),
            'created_at' => Yii::t('boss', '创建时间'),
            'updated_at' => Yii::t('boss', '更新时间'),
            'is_del' => Yii::t('boss', '是否删除'),
        ];
    }

    
}
