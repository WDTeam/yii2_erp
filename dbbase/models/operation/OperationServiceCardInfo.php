<?php

namespace dbbase\models\operation;


/**
 * This is the model class for table "ejj_operation_service_card_info".
 *
 * @property string  $id
 * @property string  $service_card_info_name
 * @property integer $service_card_info_type
 * @property integer $service_card_info_level
 * @property string  $service_card_info_value
 * @property string  $service_card_info_rebate_value
 * @property integer $service_card_info_scope
 * @property integer $service_card_info_valid_days
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class OperationServiceCardInfo extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operation_service_card_info}}';
    }

    /**
     * @inheritdoc
     * @introducntion ÎóÉ¾·ñÔòsetAttr·½·¨´íÎó
     */
    public function rules()
    {
        return [
            [['service_card_info_name','service_card_info_type', 'service_card_info_level', 'service_card_info_scope', 'service_card_info_valid_days','service_card_info_value','service_card_info_rebate_value'], 'required'],
            [['service_card_info_type', 'service_card_info_level', 'service_card_info_scope', 'service_card_info_valid_days', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['service_card_info_value', 'service_card_info_rebate_value'], 'number'],
            [['service_card_info_name'], 'string', 'max' => 64]
        ];
    }

}
