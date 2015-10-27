<?php

namespace core\models\operation;

use Yii;

/**
 * This is the model class for table "{{%operation_server_card_record}}".
 *
 * @property string $id
 * @property string $trade_id
 * @property string $cus_card_id
 * @property string $front_value
 * @property string $behind_value
 * @property string $use_value
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationServerCardRecord extends \common\models\operation\OperationServerCardRecord
{
   /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '流水号'),
            'trade_id' => Yii::t('app', '交易id'),
			'order_id' => Yii::t('app', '订单id'),
			'order_code' => Yii::t('app', '订单号'),
            'cus_card_id' => Yii::t('app', '客户服务卡'),
            'card_no' => Yii::t('app', '服务卡号'),
			'front_value' => Yii::t('app', '使用前金额'),
            'behind_value' => Yii::t('app', '使用后金额'),
            'use_value' => Yii::t('app', '服务类型'),
			'consume_type' => Yii::t('app', '业务类型'),
			'business_type' => Yii::t('app', '使用金额'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更改时间'),
        ];
    }
}
