<?php

namespace core\models\operation;

use Yii;
use common\models\operation\OperationSelectedService;
use yii\web\UploadedFile;
use crazyfd\qiniu\Qiniu;

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
class OperationSelectedService extends OperationSelectedService
{
    /**
     * 上传图片到七牛服务器
     * @param string $field 上传文件字段名
     * @return string $imgUrl 文件URL
     */
    public function uploadImgToQiniu($field){
        $qiniu = new Qiniu();
        $fileinfo = UploadedFile::getInstance($this, $field);
        if(!empty($fileinfo)){
            $key = time().mt_rand('1000', '9999').uniqid();
            $qiniu->uploadFile($fileinfo->tempName, $key);
            $imgUrl = $qiniu->getLink($key);
            $this->$field = $imgUrl;
        }
    }

}
