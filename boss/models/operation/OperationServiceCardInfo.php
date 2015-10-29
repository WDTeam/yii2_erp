<?php

namespace boss\models\operation;

use Yii;

/**
 * This is the model class for table "ejj_operation_service_card_info".
 *
 * @property string $id
 * @property string $service_card_info_name
 * @property integer $service_card_info_card_type
 * @property integer $service_card_info_card_level
 * @property string $service_card_info_par_value
 * @property string $service_card_info_reb_value
 * @property integer $service_card_info_use_scope
 * @property integer $service_card_info_valid_days
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class OperationServiceCardInfo extends \core\models\operation\OperationServiceCardInfo
{
    

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['service_card_info_card_type', 'service_card_info_card_level', 'service_card_info_use_scope', 'service_card_info_valid_days', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['service_card_info_par_value', 'service_card_info_reb_value'], 'number'],
            [['service_card_info_name'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'id'),
            'service_card_info_name' => Yii::t('app', '卡名'),
            'service_card_info_card_type' => Yii::t('app', '卡类型'),
            'service_card_info_card_level' => Yii::t('app', '卡级别'),
            'service_card_info_par_value' => Yii::t('app', '卡面金额'),
            'service_card_info_reb_value' => Yii::t('app', '优惠金额'),
            'service_card_info_use_scope' => Yii::t('app', '使用范围'),
            'service_card_info_valid_days' => Yii::t('app', '有效时间(天)'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更改时间'),
            'is_del' => Yii::t('app', '状态'),
        ];
    }
}
