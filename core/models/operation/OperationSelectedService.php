<?php

namespace core\models\operation;

use Yii;
use yii\web\UploadedFile;


/**
 * This is the model class for table "ejj_operation_selected_service".
 *
 * @property integer $id
 * @property integer $goods_id
 * @property string $scene
 * @property string $area
 * @property string $sub_area
 * @property string $standard
 * @property string $price
 * @property integer $unit
 * @property string $created_at
 * @property string $remark
 */
class OperationSelectedService extends \dbbase\models\operation\OperationSelectedService
{
    /**
     * 上传图片到七牛服务器
     * @param string $field 上传文件字段名
     * @return string $imgUrl 文件URL
     */
    public function uploadImgToQiniu($field){
        $fileinfo = UploadedFile::getInstance($this, $field);
        if(!empty($fileinfo)){
            $key = time().mt_rand('1000', '9999').uniqid();
            \Yii::$app->imageHelper->uploadFile($fileinfo->tempName, $key);
            $imgUrl = \Yii::$app->imageHelper->getLink($key);
            $this->$field = $imgUrl;
        }
    }

}
