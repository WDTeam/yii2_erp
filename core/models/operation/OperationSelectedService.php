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
     * 获取精品服务数据
     *
     * @param  int   $service_area_standard 面积标准类型
     * @return array                        精品保洁数据
     */
    public static function getSelectedServiceList($service_area_standard = ''){
        if(empty($service_area_standard) || !in_array($service_area_standard, [1, 2])){
            return '';
        }else{
            return self::find()
                ->select([
                    'id',
                    'selected_service_scene',
                    'selected_service_area',
                    'selected_service_sub_area',
                    'selected_service_standard',
                    'selected_service_area_standard',
                    'selected_service_unit',
                    'selected_service_photo',
                    'created_at',
                ])
                ->where([
                    'selected_service_area_standard' => $service_area_standard, 
                    'is_softdel' => 1,
                ])
                ->asArray()
                ->All();
        }
    }

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
