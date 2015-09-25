<?php
/**
 * 此组件依赖于:boss\models\Operation\OperationArea和表operation_area
 * 参数说明：
 * model:更改已存在数据时使用,model中应包含属性：province_id,[city_id],[county_id],[town_id],至少要包含第一，或者可以包含第一个和第二个以此类推;
 * options:控件的属性设置
 * label:上分或者前方显示的名称
 * grades:你希望显示几级：一级：province;二级：city;三级：county; 四级：town 或 不含这个参数
 * 
 * 如何调用：
 * echo AreaCascade::widget([
        'model' => $model,
        'name' => 'OperationCity',
        'options' => ['class' => 'form-control inline'],
        'label' =>'选择城市',
        'grades' => 'city',
    ]);
 */
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
    
    public $model;
    public $options;
    public $label;
    public $grades = 'town';
    private $name;
    private $html;
    private $province_id;
    private $city_id;
    private $county_id;
    private $town_id;


    public function init() {
        parent::init();
        $this->getEditInfo();
        $this->cascadeAll();
    }
    
    /**
     * 
     */
    public function cascadeAll(){
        if(empty($this->grades)){$this->grades = 'town';}
        $province_selection = empty($this->province_id) ? self::_PROVINCE_ : $this->province_id;
        $city_selection = empty($this->city_id) ? self::_CITY_ : $this->city_id;
        $county_selection = empty($this->county_id) ? self::_COUNTY_ : $this->county_id;
        $town_selection = empty($this->town_id) ? self::_TOWN_ : $this->town_id;
        if($this->grades == 'province'){
            //显示一级
            $this->options['showend'] = 'yes';
            $this->html = $this->area($this->options, $province_selection, 'province', 0);
        }elseif($this->grades == 'city'){
            //显示两级
            $this->html = $this->area($this->options, $province_selection, 'province', 0);
            $this->options['showend'] = 'yes';
            $this->html .= $this->area($this->options, $city_selection, 'city', $this->province_id);
        }elseif($this->grades == 'county'){
            //显示三级
            $this->html = $this->area($this->options, $province_selection, 'province', 0);
            $this->html .= $this->area($this->options, $city_selection, 'city', $this->province_id);
            $this->options['showend'] = 'yes';
            $this->html .= $this->area($this->options, $county_selection, 'county', $this->city_id);
        }elseif($this->grades == 'town'){
            //显示四级
            $this->html = $this->area($this->options, $province_selection, 'province', 0);
            $this->html .= $this->area($this->options, $city_selection, 'city', $this->province_id);
            $this->html .= $this->area($this->options, $county_selection, 'county', $this->city_id);
            $this->options['showend'] = 'yes';
            $this->html .= $this->area($this->options, $town_selection, 'town', $this->county_id);
        }
    }
    
    
    public function area($options, $selection, $type = 'town', $parent_id = ''){
        $this->name = $this->getClassName($this->model);
        $name = $this->name.'['.$type.'_id]';
        $options['id'] = $type;
        if(!is_numeric($selection)){
            $items = [$selection];
        }
        if($parent_id !== ''){
            $data = OperationArea::getProvinces($parent_id);
            foreach($data as $key => $value){
                $items[$value->id] = $value->area_name;
            }
        }
        return Html::dropDownList($name, $selection, $items, $options);
    }
    
    private function getClassName(){
        $class = get_class($this->model);
        $classes = explode('\\', $class);
        return $classes[count($classes)-1];
    }


    private function getEditInfo(){
        if(isset($this->model->province_id)){
             $this->province_id = $this->model->province_id;
        }
        if(isset($this->model->city_id)){
             $this->city_id = $this->model->city_id;
        }
        if(isset($this->model->county_id)){
             $this->county_id = $this->model->county_id;
        }
        if(isset($this->model->town_id)){
             $this->town_id = $this->model->town_id;
        }
    }

    public function run(){
        return $this->render('AreaCascade', ['name' => $this->name, 'html' => $this->html, 'label' =>$this->label]);
    }
}
