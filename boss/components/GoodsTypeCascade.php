<?php
namespace boss\components;

use Yii;
use boss\models\operation\OperationCategory;
use yii\helpers\Html;
use yii\base\Widget;
//use kartik\widgets\Select2;

/**
 * 
 */
class GoodsTypeCascade extends \yii\widgets\InputWidget
{
    public $label = '选择商品分类';
    public $html = '';
  
    public function init(){
        parent::init();
        $this->cascadeAll();
    }
    
    public function cascadeAll($operation_category_parent_id = 0){
        $data = OperationCategory::getCategoryList($operation_category_parent_id);
        $items = array();
        foreach((array)$data as $key => $value){
            $items[$value->id] = $value->operation_category_name;
        }

        $this->html = $this->type($items);
    }
    
    public function type($items){
        $class="col-md-6";
        $name = array();
        return '<div class="'.$class.'" style="padding:0 1px;">'.Html::dropDownList($this->name, [], $items, []).'</div>';
    }
    
    public function run(){
        return $this->render('GoodsTypeCascade', ['name' => $this->name, 'html' => $this->html, 'label' =>$this->label]);
    }

}
