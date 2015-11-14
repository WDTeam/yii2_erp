<?php

namespace core\models\operation;

use Yii;
use dbbase\models\operation\OperationArea as CommonOperationArea;

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
class OperationArea extends CommonOperationArea
{
    public static function getAreaList($parent_id){
        $data = self::find()->select(['id', 'area_name'])->asArray()->where(['parent_id' => $parent_id])->all();
        $d = array();
        foreach((array)$data as $key => $value){
            $d[$value['id'].'_'.$value['area_name']] = $value['area_name'];
        }
        return $d;
    }
    

	/**
    * 获取城市名称
    * @date: 2015-10-27
    * @author: peak pan
    * @return:
    **/
    public static function getAreaname($parent_id){
    	$data = self::find()->select(['area_name'])->asArray()->where(['id' => $parent_id])->one();
    	return $data['area_name'];
    }

    
    
    
    
    
    /**
    * 函数用途描述
    * @date: 2015-11-11
    * @author: peak pan
    * @return:
    **/
    public static function getAreaid($name){
    	
    	$data = self::find()->select(['id'])
    	->where(['and','parent_id!=0'])
    	->andFilterWhere(['like', 'area_name',$name])
    	->asArray()
    	->one();
    	
    	if($data['id']){
    		return $data['id'];
    	}else{
    		return 0;
    	}
    	
    }
    
    

    public static function getProvinces($parent_id = 0){
        $where = ['parent_id' => $parent_id];
        return self::getAllData($where);
    }
}
