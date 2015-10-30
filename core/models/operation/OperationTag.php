<?php

namespace core\models\operation;

use Yii;
use dbbase\models\operation\OperationTag as CommonOperationTag;

/**
 * This is the model class for table "{{%operation_tag}}".
 *
 * @property integer $id
 * @property string $operation_tag_name
 * @property integer $operation_applicable_scope_id
 * @property string $operation_applicable_scope_name
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationTag extends CommonOperationTag
{
    public static function setTagInfo($tags){
        $fields = ['operation_tag_name', 'operation_applicable_scope_id', 'operation_applicable_scope_name', 'created_at', 'updated_at'];
//        $data = array();
//        $tagcontent = array();
        foreach((array)$tags as $key => $value){
            if(!empty($value)) {
                $taginfo = self::find()->where(['operation_tag_name' => $value])->One();
                if (empty($taginfo)) {
                    $d[$key][] = $value;
                    $d[$key][] = 1;
                    $d[$key][] = '商品';
                    $d[$key][] = time();
                    $d[$key][] = time();

//                $tagcontent[] = $d[$key];
//                Yii::$app->db->createCommand()->batchInsert(self::tableName(), $fields, $tagcontent)->execute();
//                $data[Yii::$app()->db->getLastInsertID()] = $value;
//                unset($tagcontent);
                }
            }
        }
//        return $data;
        if(!empty($d)){
            Yii::$app->db->createCommand()->batchInsert(self::tableName(), $fields, $d)->execute();
        }
    }
}
