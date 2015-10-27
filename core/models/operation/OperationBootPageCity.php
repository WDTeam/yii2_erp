<?php
namespace core\models\operation;
use Yii;
use common\models\operation\OperationBootPageCity as CommonOperationBootPageCity;

/**
 * This is the model class for table "{{%operation_boot_page_city}}".
 *
 * @property integer $id
 * @property integer $operation_boot_page_id
 * @property integer $operation_city_id
 * @property string $operation_city_name
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationBootPageCity extends CommonOperationBootPageCity
{
    public static function getBootPageCityList($BootPageId){
        $data = self::find()->where(['operation_boot_page_id' => $BootPageId])->all();
        $d = array();
        foreach((array)$data as $key => $value){
            $d[] = $value['operation_city_id'];
        }
        return $d;
    }
    
    public static function setBootPageCityList($citylist, $BootPageId){
        self::deleteAll(['operation_boot_page_id' => $BootPageId]);
        $d = array();
        $fields = ['operation_boot_page_id', 'operation_city_id', 'operation_city_name', 'created_at', 'updated_at'];
        foreach((array)$citylist as $key => $value){
            $cityinfo = explode('-', $value);
            $d[$key][] = $BootPageId;
            $d[$key][] = $cityinfo[0];
            $d[$key][] = $cityinfo[1];
            $d[$key][] = time();
            $d[$key][] = time();
        }
        Yii::$app->db->createCommand()->batchInsert(self::tableName(), $fields, $d)->execute();
    }
    
    public static function delBootPageCityList($BootPageId){
        self::deleteAll(['operation_boot_page_id' => $BootPageId]);
    }
}
    
