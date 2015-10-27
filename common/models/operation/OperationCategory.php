<?php

namespace common\models\operation;

use Yii;
use common\models\operation\OperationCategoryQuery;

/**
 * This is the model class for table "{{%operation_category}}".
 *
 * @property integer $id
 * @property string $operation_category_name
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operation_category}}';
    }

    /**
     * @inheritdoc
     * @return OperationCategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OperationCategoryQuery(get_called_class());
    }
    
    public static function getAllData($where, $orderby = '', $select){
        return self::find()->select($select)->asArray()->where($where)->orderby($orderby)->all();
    }
}
