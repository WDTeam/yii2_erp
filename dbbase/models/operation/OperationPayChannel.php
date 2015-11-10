<?php

namespace dbbase\models\operation;

use Yii;

/**
 * This is the model class for table "{{%operation_pay_channel}}".
 *
 * @property integer $id
 * @property string $operation_pay_channel_name
 * @property integer $operation_pay_channel_type
 * @property string $operation_pay_channel_rate
 * @property integer $system_user_id
 * @property string $system_user_name
 * @property integer $create_time
 * @property integer $is_del
 */
class OperationPayChannel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operation_pay_channel}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['operation_pay_channel_type', 'system_user_id', 'create_time', 'is_del'], 'integer'],
            [['operation_pay_channel_name'], 'string', 'max' => 50],
            [['operation_pay_channel_rate'], 'string', 'max' => 6],
            [['system_user_name'], 'string', 'max' => 40]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '主键id'),
            'operation_pay_channel_name' => Yii::t('app', '支付渠道名称'),
            'operation_pay_channel_type' => Yii::t('app', '支付渠道类别'),
            'operation_pay_channel_rate' => Yii::t('app', '比率'),
            'system_user_id' => Yii::t('app', '添加人id'),
            'system_user_name' => Yii::t('app', '添加人名称'),
            'create_time' => Yii::t('app', '增加时间'),
            'is_del' => Yii::t('app', '0 正常 1 删除'),
        ];
    }

  
   
}
