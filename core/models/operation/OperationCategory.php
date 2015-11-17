<?php

namespace core\models\operation;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%operation_category}}".
 *
 * @property integer $id
 * @property string $operation_category_name
 * @property integer $created_at
 * @property integer $updated_at
 */
class OperationCategory extends \dbbase\models\operation\OperationCategory
{
    public static function getCategoryList($operation_category_parent_id = 0, $orderby = '', $select = null)
    {
        return self::getAllData(['operation_category_parent_id' => $operation_category_parent_id], '', $select);
    }

    public static function getCategoryName($operation_category_id)
    {
        $data = self::find()->select(['operation_category_name'])->where(['id' => $operation_category_id])->one();
        return $data->operation_category_name;
    }
    
    /**
     * edit by TianYuxing
     * 根据服务品类ID获取对应的服务品类详情
     *
     * @param  integer $operation_category_id 服务品类ID
     * @return array
     */
    public static function getCategoryById($operation_category_id)
    {
        return  self::find()
                ->select(['operation_category_name','operation_category_icon','operation_category_price_description'])
                ->where(['id' => $operation_category_id])
                ->asArray()
                ->one();
    }

    public static function getAllCategory()
    {
    	$cacheName = 'getAllCategory';
    	//判断是否存在缓存
    	$cache=Yii::$app->cache->get($cacheName);
    	 if($cache){
    	 return $cache;
    	} else{
    		$name=self::getAllData('', 'sort', 'id,operation_category_name');
    		Yii::$app->cache->set($cacheName,$name,180);
    		return  $name;
    	}
    }

    /**
     * 上传图片到七牛服务器
     * @param string $field 上传文件字段名
     * @return string $imgUrl 文件URL
     */
    public function uploadImgToQiniu($field){
        $fileinfo = UploadedFile::getInstance($this, $field);
        if (!empty($fileinfo)) {
            $key = time().mt_rand('1000', '9999').uniqid();
            \Yii::$app->imageHelper->uploadFile($fileinfo->tempName, $key);
            $imgUrl = \Yii::$app->imageHelper->getLink($key);
            $this->$field = $imgUrl;
        }
    }
}
