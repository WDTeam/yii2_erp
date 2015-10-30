<?php

namespace core\models\customer;

use Yii;

/**
 * This is the model class for table "{{%customer_ext_src}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property string $platform_name
 * @property string $channal_name
 * @property string $device_name
 * @property string $device_no
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class CustomerExtSrc extends \dbbase\models\customer\CustomerExtSrc
{
    /**
     * 获取最初来源
     */
    public static function getFirstSrc($customer_id){
        $customerExtSrc = self::find()->where(['customer_id'=>$customer_id])->orderBy('created_at asc')->one();
        return $customerExtSrc == NULL ? false : $customerExtSrc;
    }
}
