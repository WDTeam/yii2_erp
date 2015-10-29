<?php

namespace core\models\operation;

use Yii;
use dbbase\models\operation\OperationCategory as CommonOperationCategory;
/**
 * This is the model class for table "{{%operation_category}}".
 *
 * @property integer $id
 * @property string $operation_category_name
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationCategory extends CommonOperationCategory
{
    public static function getCategoryList($operation_category_parent_id = 0, $orderby = '', $select = null){
        return self::getAllData(['operation_category_parent_id' => $operation_category_parent_id], '', $select);
    }

    public static function getCategoryName($operation_category_id){
        $data = self::find()->select(['operation_category_name'])->where(['id' => $operation_category_id])->one();
        return $data->operation_category_name;
    }

    public static function getAllCategory()
    {
        return self::getAllData('', 'sort', 'id,operation_category_name');

    }
}
