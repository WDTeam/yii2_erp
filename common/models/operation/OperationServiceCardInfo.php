<?php

namespace common\models\operation;


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
class OperationServiceCardInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operation_service_card_info}}';
    }

}
