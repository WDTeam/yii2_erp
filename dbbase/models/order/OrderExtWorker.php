<?php

namespace dbbase\models\order;

use Yii;
use dbbase\models\ActiveRecord;
/**
 * This is the model class for table "{{%order_ext_worker}}".
 *
 * @property string $order_id
 * @property string $worker_id
 * @property string $order_worker_phone
 * @property string $order_worker_name
 * @property string $order_worker_memo
 * @property string $worker_type_id
 * @property string $order_worker_type_name
 * @property integer $order_worker_assign_type
 * @property integer $order_worker_assign_time
 * @property string $shop_id
 * @property string $order_worker_shop_name
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Order $order
 */
class OrderExtWorker extends ActiveRecord
{
    const ASSIGN_TYPE_CS = 1; //客服指派
    const ASSIGN_TYPE_SHOP = 2; //门店指派
    const ASSIGN_TYPE_WORKER = 3; //阿姨端接单
    const ASSIGN_TYPE_IVR = 4; //IVR接单
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_ext_worker}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['worker_id', 'worker_type_id', 'order_worker_assign_type','order_worker_assign_time', 'shop_id', 'created_at', 'updated_at'], 'integer'],
            [['order_worker_type_name','order_worker_phone','order_worker_name'], 'string', 'max' => 64],
            [['order_worker_memo','order_worker_shop_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => '订单id',
            'worker_id' => '工人id',
            'order_worker_phone' => '工人手机号',
            'order_worker_name' => '工人姓名',
            'order_worker_memo' => '工人备注',
            'worker_type_id' => '工人职位类型ID',
            'order_worker_type_name' => '工人职位类型',
            'order_worker_assign_type' => '工人接单方式 0未接单 1工人抢单 2客服指派 3门店指派',
            'order_worker_assign_time' => '接单时间',
            'shop_id' => '工人所属门店id',
            'order_worker_shop_name' => '工人所属门店',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
}
