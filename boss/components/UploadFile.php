<?php
namespace boss\components;

use Yii;
use crazyfd\qiniu\Qiniu;
use yii\web\UploadedFile;
use yii\base\Widget;
/**
 * 
 */
class UploadFile extends Widget{
    private $key;
    public $file;
    public function init() {
        parent::init();
        $this->$key = time().mt_rand('1000', '9999');
        
    }
    
    public function upfile(){
        $file = UploadedFile::widget(['operation_boot_page_ios_img']);
        $qiniu = new Qiniu();
        $qiniu->uploadFile($file->tempName, $this->key);
    }
    
    private function getFile($file_input_name){
        $file = UploadedFile::widget([$file_input_name]);
    }

    public function run(){
        return $this->render('Upload');
    }
}
