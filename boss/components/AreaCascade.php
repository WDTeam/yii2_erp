<?php
namespace boss\components;

use Yii;
use boss\models\Operation\OperationArea;
use yii\helpers\Html;
use yii\base\Widget;

/**
 * 
 */
class AreaCascade extends Widget{

    const _PROVINCE_ = '选择省(直辖市)';
    const _CITY_ = '选择城市';
    const _COUNTY_ = '选择县（区）';
    const _TOWN_ = '选择乡镇（街道）';
    
    public $name;
    public $options;
    public $label;
    public $grades = 'town';
    private $html;
    
    public function init() {
        parent::init();
        $this->cascadeAll();
    }
    
    /**
     * 
     */
    public function cascadeAll(){
        if(empty($this->grades)){$this->grades = 'town';}
        if($this->grades == 'province'){
            $this->options['showend'] = 'yes';
            $this->html = $this->area($this->options, self::_PROVINCE_, 'province', 0);
        }elseif($this->grades == 'city'){
            $this->html = $this->area($this->options, self::_PROVINCE_, 'province', 0);
            $this->options['showend'] = 'yes';
            $this->html .= $this->area($this->options, self::_CITY_, 'city');
        }elseif($this->grades == 'county'){
            $this->html = $this->area($this->options, self::_PROVINCE_, 'province', 0);
            $this->html .= $this->area($this->options, self::_CITY_, 'city');
            $this->options['showend'] = 'yes';
            $this->html .= $this->area($this->options, self::_COUNTY_, 'county');
        }elseif($this->grades == 'town'){
            $this->html = $this->area($this->options, self::_PROVINCE_, 'province', 0);
            $this->html .= $this->area($this->options, self::_CITY_, 'city');
            $this->html .= $this->area($this->options, self::_COUNTY_, 'county');
            $this->options['showend'] = 'yes';
            $this->html .= $this->area($this->options, self::_TOWN_);
        }
    }
    
    
    public function area($options, $selection, $type = 'town', $parent_id = ''){
        $name = $this->name.'['.$type.']';
        $options['id'] = $type;
        $items = [$selection];
        if($parent_id !== ''){
            $data = OperationArea::getProvinces($parent_id);
            foreach($data as $key => $value){
                $items[$value->id] = $value->area_name;
            }
        }
        return Html::dropDownList($name, '', $items, $options);
    }

    public function run(){
        return $this->render('AreaCascade', ['name' => $this->name, 'html' => $this->html, 'label' =>$this->label]);
    }
}
