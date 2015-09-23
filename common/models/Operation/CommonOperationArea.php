<?php

namespace common\models\Operation;

use Yii;

/**
 * This is the model class for table "{{%operation_area}}".
 *
 * @property integer $id
 * @property string $area_name
 * @property integer $parent_id
 * @property string $short_name
 * @property string $longitude
 * @property string $latitude
 * @property integer $level
 * @property string $position
 * @property integer $sort
 */
class CommonOperationArea extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operation_area}}';
    }
    
    public static function getAllData($where, $orderby = 'sort ASC'){
        return self::find()->where($where)->orderby($orderby)->all();
    }
}
