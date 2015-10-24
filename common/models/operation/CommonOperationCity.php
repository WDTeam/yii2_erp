<?php

namespace common\models\operation;

use Yii;

/**
 * This is the model class for table "{{%operation_city}}".
 *
 * @property integer $id
 * @property string $operation_city_name
 * @property integer $operation_city_is_online
 * @property integer $created_at
 * @property integer $updated_at
 */
class CommonOperationCity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operation_city}}';
    }
    
    
    public static function getOnlineCity(){
        return static::find()->where(['operation_city_is_online' => 1])->all();
    }
}
