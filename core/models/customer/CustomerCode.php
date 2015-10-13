<?php

namespace core\models\customer;

use Yii;

/**
 * This is the model class for table "{{%customer_code}}".
 *
 * @property integer $id
 * @property string $customer_code
 * @property integer $customer_code_expiration
 * @property string $customer_phone
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class CustomerCode extends \common\models\CustomerCode
{

    /**
     * 生成验证码并发送
     */
    public static function generateAndSend($phone){
        //
    }
}
