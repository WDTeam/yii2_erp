<?php
namespace boss\models;
use yii;
class ShopManager extends \common\models\ShopManager
{
    public $bl_types = [
        1=>'个体户',
        2=>'商户'
    ];
}