<?php
/**
 * 搜索框插件：
 * 调用方法： SearchBox::widget([]);
 * 参数：
 * action:提交的目的地址
 * method:提交方式 POST/GET
 * options: 搜索form的其他属性
 * type:是否按照指定字段搜索，需要：Field，不需要：缺省
 * keyword_value：默认显示的搜索关键字
 * keyword_options ：搜索框的默认属性
 * submit_options ： 提交按钮的默认属性
 * fields ： 可查询的字段
 * default： 默认搜索的字段
 * 
 * demo位置：boss/views/operation-city/index.php
 * 
 * 
 * <?php
    echo SearchBox::widget([
        'action' => ['index'],
        'method' => 'POST',
        'options' => [],
        'type' => 'Field',
        'keyword_value' => $params['keyword'],
        'keyword_options' => ['placeholder' => '搜索关键字', 'class' => 'form-control'],
        'submit_options' => ['class' => 'btn btn-default form-control'],
        'fields' => ['搜索字段', 'province_name' => '省份名称', 'city_name' => '城市名称'],
        'default' => $params['fields'],
    ]);
    ?>
 */

namespace boss\components;

use Yii;
use yii\helpers\Html;
use yii\base\Widget;
//use kartik\widgets\Select2;
/**
 * 
 */
class SearchBox extends Widget{
    public $action;
    public $method;
    public $options;
    public $type;
    public $keyword_value;
    public $keyword_options;
    public $submit_options;
    public $fields;
    public $default;
    private $html;

    public function init() {
        parent::init();
        if(empty($this->type)){$this->type = '';}
        $func = 'set'.$this->type.'SearchForm';
        $this->$func();
    }
    
    public function setFieldSearchForm(){
        $selection = empty($this->default) ? '选择字段' : $this->default;
//        $this->html = '<div class="form-group col-sm-3">'.Select2::widget([
//            'name' => 'fields',
//            'data' => $this->fields,
//            'options' => [
//                'placeholder' => Yii::t('app', $selection),
//                'multiple' => false,
//                'class' => 'form-control'
//            ],
//        ]).'</div>';
        $this->html = '<div class="form-group col-sm-3">'.Html::dropDownList('fields', $selection, $this->fields, ['class' =>'form-control inline']).'</div>';
        
        $this->html .= '<div class="form-group col-sm-7">'.Html::textInput('keyword', $this->keyword_value, $this->keyword_options).'</div>';
        $this->html .= '<div class="form-group col-sm-2">'.Html::submitButton('搜索', $this->submit_options).'</div>';
    }
    
    public function setSearchForm(){
        $this->html = '<div class="form-group col-sm-10">'.Html::textInput('keyword', $this->keyword_value, $this->keyword_options).'</div>';
        $this->html .= '<div class="form-group col-sm-2">'.Html::submitButton('搜索', $this->submit_options).'</div>';
    }

    public function run(){
        return $this->render('SearchBox', ['html' => $this->html]);
    }
}
