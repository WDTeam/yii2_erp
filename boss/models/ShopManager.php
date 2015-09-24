<?php
namespace boss\models;
use yii;
class ShopManager extends \common\models\ShopManager
{
    public static $bl_types = [
        1=>'个体户',
        2=>'商户'
    ];
    
    public static $audit_statuses = [
        0=>'未审核',
        1=>'通过',
        2=>'不通过'
    ];
}