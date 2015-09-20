<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%finance_settle_apply}}".
 *
 * @property integer $id
 * @property integer $worder_id
 * @property string $settle_apply_money
 * @property integer $settle_apply_status
 * @property integer $isdel
 * @property integer $updated_at
 * @property integer $created_at
 */
class FinanceSettleApply extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance_settle_apply}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['worder_id', 'settle_apply_money', 'settle_apply_status'], 'required'],
            [['worder_id', 'settle_apply_status', 'isdel', 'updated_at', 'created_at'], 'integer'],
            [['settle_apply_money'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'worder_id' => Yii::t('app', 'Worder ID'),
            'settle_apply_money' => Yii::t('app', 'Settle Apply Money'),
            'settle_apply_status' => Yii::t('app', 'Settle Apply Status'),
            'isdel' => Yii::t('app', 'Isdel'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }
}
