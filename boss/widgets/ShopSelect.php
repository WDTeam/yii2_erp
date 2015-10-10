<?php
/**
 * Created by PhpStorm.
 * User: colee
 * Date: 2015/10/10
 * Time: 10:49
 */

namespace boss\widgets;


use boss\models\ShopManager;
use yii\helpers\ArrayHelper;
use yii\widgets\InputWidget;

class ShopSelect extends InputWidget
{
    public $name = 'shop_select';
    public $shop_manager_id;
    public $shop_id;

    private $shop_manages;
    public function getShopManagerArray()
    {
        $models = ShopManager::find()->select(['id','name'])->where('isdel is NULL or isdel=0')->all();
        return ArrayHelper::map($models, 'id', 'name');
    }
    public function run()
    {
        return $this->render('shop_select',[
            'shop_managers'=>$this->getShopManagerArray(),
            'widget_id'=>$this->id,
        ]);
    }
}