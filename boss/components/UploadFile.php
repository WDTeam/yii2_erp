<?php
/**
 * 文件上传，直接上传到七牛
 * 参数说明：
 * fileInputName ： 文件域的name；
 * 使用方法：
 * use boss\components\UploadFile;
 * $path = UploadFile::widget(['fileInputName' => 'file']);
 */


namespace boss\components;

use Yii;
use yii\web\UploadedFile;
use yii\base\Widget;
use yii\base\Object;
/*
    $this->accessKey = 'kaMuZPkS_f_fxcfsDKET0rTst-pW6Ci7GMlakffw';
    $this->secretKey = 'HEMGszOQBpQEC_GMqFqT_mwQW0ypQoE0Y3uhCllq';
    $this->domain = '7b1f97.com1.z0.glb.clouddn.com';
    $this->bucket = 'bjzhichangmusic';
    //这是成江的号
 */
class UploadFile extends Widget{
    private $key;
    private $file;
    public $fileInputName;
    private $dest_path;
    public function init() {
        parent::init();
        $this->getFile();
        $this->key = time().mt_rand('1000', '9999');
        $this->upfile();
    }
    
    public function upfile(){
        $qiniu = \Yii::$app->imageHelper;
        $qiniu->uploadFile($this->file['tmp_name'], $this->key);
        $this->dest_path = $qiniu->getLink($this->key);//.'?e='. $this->getUnixTime();
//        $token = $qiniu->Sign($downUrl);
////        $token = $qiniu->accessToken($downUrl);
////        $token = '2JcEasZbcnt02g3BAKO6s_gFoTEDqk5hlGOzYuD7:'.$token;
//        $this->dest_path = $downUrl.'&token='.$token;
    }
    
    public function getUnixTime(){
        return time()+86400*365;
    }

    public function getFile(){
        $this->file = $_FILES[$this->fileInputName];
    }

    public function run(){
        return $this->dest_path;
    }
}
