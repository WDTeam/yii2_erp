<?php

namespace core\models\Operation;

use Yii;
use common\models\Operation\CommonOperationCategory;
/**
 * This is the model class for table "{{%operation_category}}".
 *
 * @property integer $id
 * @property string $operation_category_name
 * @property integer $created_at
 * @property integer $updated_at
 */
class CoreOperationCategory extends CommonOperationCategory
{
    public static function getCategoryList($operation_category_parent_id = 0, $orderby = '', $select = null){
        return self::getAllData(['operation_category_parent_id' => $operation_category_parent_id], '', $select);
    }

    public static function getCategoryName($operation_category_id){
        $data = self::find()->select(['operation_category_name'])->where(['id' => $operation_category_id])->one();
        return $data->operation_category_name;
    }
}
