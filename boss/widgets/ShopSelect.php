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


use core\models\shop\Shop;
use core\models\shop\ShopManager;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\InputWidget;
use core\models\system\SystemUser;
use core\models\auth\AuthItem;

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
    /**
     * 小家政列表，不包含自营家政
     */
    public function getShopManagerArray()
    {
        $query = ShopManager::find()->select(['id','name'])
        ->where('(isdel is NULL or isdel=0) AND id>1');
        if(!\Yii::$app->user->can(AuthItem::SYSTEM_ROLE_ADMIN)){
            $shop_manager_ids = \Yii::$app->user->identity->getShopManagerIds();
            $query->andFilterWhere(['in','id', $shop_manager_ids]);
        }
        $models = $query->all();
        return ArrayHelper::map($models, 'id', 'name');
    }
    public function getShopArray()
    {
        $manager_id = (int)$this->model->getAttribute($this->shop_manager_id);
        $query = Shop::find()->select(['id','name'])
            ->where('(isdel is NULL OR isdel=0) AND shop_manager_id='.$manager_id);
        if(!\Yii::$app->user->can(AuthItem::SYSTEM_ROLE_ADMIN)){
            $shop_ids = \Yii::$app->user->identity->getShopIds();
            $query->andFilterWhere(['in','id', $shop_ids]);
        }
        $models = $query->all();
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