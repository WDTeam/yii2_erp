<?php
/**
 * Created by PhpStorm.
 * User: colee
 * Date: 2015/10/10
 * Time: 10:49
 *
 * 用法： <?php echo \boss\widgets\ShopSelect::widget([
            'model'=>$model,
            'shop_manager_id'=>'shop_manager_id',
            'shop_id'=>'shop_id',
            ]);?>
 */

namespace boss\widgets;


use boss\models\Shop;
use boss\models\ShopManager;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\InputWidget;

class ShopSelect extends InputWidget
{
    public $model;
    public $name = 'shop_select';
    public $shop_manager_id='shop_manager_id';
    public $shop_id='shop_id';

    public function init()
    {
        if($this->model instanceof Model){
//            $this->shop_manager_id = Html::getInputName($this->model, $this->shop_manager_id);
//            $this->shop_id = Html::getInputName($this->model, $this->shop_id);
        }else{
            throw new InvalidConfigException('model不能为空');
        }
        parent::init();
    }
    public function getShopManagerArray()
    {
        $models = ShopManager::find()->select(['id','name'])->where('isdel is NULL or isdel=0')->all();
        return ArrayHelper::map($models, 'id', 'name');
    }
    public function getShopArray()
    {
        $manager_id = (int)$this->model->getAttribute($this->shop_manager_id);
        $models = Shop::find()->select(['id','name'])
            ->where('(isdel is NULL OR isdel=0) AND shop_manager_id='.$manager_id)->all();
        return ArrayHelper::map($models, 'id', 'name');
    }
    public function run()
    {
        return $this->render('shop_select',[
            'shop_managers'=>$this->getShopManagerArray(),
            'shops'=>$this->getShopArray(),
            'widget_id'=>$this->id,
            'model'=>$this->model,
            'shop_manager_id'=>$this->shop_manager_id,
            'shop_id'=>$this->shop_id,
        ]);
    }
}