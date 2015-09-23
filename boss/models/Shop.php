<?php
namespace boss\models;
use yii;
class Shop extends \common\models\Shop
{
    public static $audit_statuses = [
        0=>'未审核',
        1=>'通过',
        2=>'不通过'
    ];
}