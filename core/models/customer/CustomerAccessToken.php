<?php

namespace core\models\customer;

use Yii;


/**
 * This is the model class for table "{{%customer_access_token}}".
 *
 * @property integer $id
 * @property string $customer_access_token
 * @property integer $customer_access_token_expiration
 * @property integer $customer_code_id
 * @property string $customer_code
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class CustomerAccessToken extends \common\models\CustomerAccessToken
{
    
}
