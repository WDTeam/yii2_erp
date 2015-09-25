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
        $qiniu = new Qiniu();
        $qiniu->uploadFile($file->tempName, $this->key);
    }
    
    private function getFile($file_input_name){
        $file = UploadedFile::widget(['name' => $file_input_name]);
        $this->file = $file;
    }

    public function run(){
        return $this->render('Upload');
    }
}
