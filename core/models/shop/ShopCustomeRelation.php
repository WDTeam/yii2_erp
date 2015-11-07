<?php

namespace core\models\shop;

use Yii;

/**
 * This is the model class for table "ejj_shop_custome_relation".
 *
 * @property string $id
 * @property integer $system_user_id
 * @property integer $baseid
 * @property integer $shopid
 * @property integer $shop_manager_id
 * @property integer $stype
 * @property integer $is_del
 */
class ShopCustomeRelation extends \dbbase\models\shop\ShopCustomeRelation
{
    const TYPE_STYPE_SHOPMANAGER = 1; //家政公司
    const TYPE_STYPE_SHOP = 2; //门店
}
