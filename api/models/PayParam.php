<?php
namespace api\models;

use \yii\db\ActiveRecord;

class PayParam extends ActiveRecord
{
    public $pay_money;
    public $customer_id;
    public $channel_id;
    public $partner;
    public $order_id;

    public function rules()
    {
        return [
            [['pay_money','customer_id','channel_id','partner','order_id'],'required'],
        ];
    }
}