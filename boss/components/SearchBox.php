<?php
namespace boss\components;

use Yii;
use boss\models\Operation\OperationArea;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use yii\base\Widget;

/**
 * 
 */
class AreaCascade extends Widget{

    public function init() {
        parent::init();
        
    }
    
    

    public function run(){
        return $this->render('SearchBox');
    }
}
