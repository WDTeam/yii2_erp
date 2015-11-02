<?php

namespace common\models\operation;


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

    public $id;
    public $service_card_info_name;
    public $service_card_info_type;
    public $service_card_info_level;
    public $service_card_info_value;
    public $service_card_info_rebate_value;
    public $service_card_info_scope;
    public $service_card_info_valid_days;
    public $created_at;
    public $updated_at;
    public $is_del;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operation_service_card_info}}';
    }

}
