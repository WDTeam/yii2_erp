<?php

namespace boss\models\operation;

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
class OperationServerCardRecord extends \core\models\operation\OperationServerCardRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'et_operation_server_card_record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['trade_id', 'cus_card_id', 'created_at', 'updated_at'], 'integer'],
            [['front_value', 'behind_value', 'use_value'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'id'),
            'trade_id' => Yii::t('app', '交易id'),
            'cus_card_id' => Yii::t('app', '客户服务卡'),
            'front_value' => Yii::t('app', '使用前金额'),
            'behind_value' => Yii::t('app', '使用后金额'),
            'use_value' => Yii::t('app', '使用金额'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更改时间'),
        ];
    }
}
