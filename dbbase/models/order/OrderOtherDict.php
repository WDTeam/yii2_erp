<?php

namespace dbbase\models\order;

use Yii;
use dbbase\models\ActiveRecord;
/**
 * This is the model class for table "{{%order_other_dict}}".
 *
 * @property integer $id
 * @property string $order_other_dict_type
 * @property string $order_other_dict_name
 * @property string $created_at
 * @property string $updated_at
 * @property integer $isdel
 */
class OrderOtherDict extends ActiveRecord
{
    const TYPE_CANCEL_ORDER_COMPANY_CAUSE = 1;
    const TYPE_CANCEL_ORDER_CUSTOMER_CAUSE = 2;
    const TYPE_ORDER_WORKER_RELATION_STATUS = 3;

    const NAME_WORKER_CONTACT_FAILURE = 1;
    const NAME_WORKER_REFUSE = 2;
    const NAME_CANCEL_ASSIGN = 3;
    const NAME_IVR_PUSH_SUCCESS = 4;
    const NAME_IVR_PUSH_FAILURE = 5;
    const NAME_JPUSH_PUSH_SUCCESS = 6;
    const NAME_JPUSH_PUSH_FAILURE = 7;

    const NAME_CANCEL_ORDER_CUSTOMER_OTHER_CAUSE = 8;  //其它原因
    const NAME_CANCEL_ORDER_CUSTOMER_PAY_FAILURE = 15; //支付异常


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_other_dict}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_other_dict_type', 'created_at', 'updated_at', 'isdel'], 'integer'],
            [['order_other_dict_name'], 'required'],
            [['order_other_dict_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_other_dict_type' => '字典类型 1取消订单公司原因 2取消订单个人原因 3订单阿姨关系状态',
            'order_other_dict_name' => '字典名称',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'isdel' => 'Isdel',
        ];
    }

    public static function getName($id)
    {
        return self::findOne($id)->order_other_dict_name;
    }
}
