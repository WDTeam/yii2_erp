<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%order}}".
 *
 * @property string $id
 * @property string $order_code
 * @property string $order_parent_id
 * @property integer $order_is_parent
 * @property string $created_at
 * @property string $updated_at
 * @property integer $isdel
 * @property integer $order_ip
 * @property integer $order_service_type_id
 * @property string $order_service_type_name
 * @property integer $order_src_id
 * @property string $order_src_name
 * @property string $channel_id
 * @property string $order_channel_name
 * @property string $order_unit_money
 * @property string $order_money
 * @property string $order_booked_count
 * @property string $order_booked_begin_time
 * @property string $order_booked_end_time
 * @property string $address_id
 * @property string $order_address
 * @property string $order_booked_worker_id
 * @property string $checking_id
 * @property string $order_cs_memo
 *
 * @property OrderExtCustomer $orderExtCustomer
 * @property OrderExtFlag $orderExtFlag
 * @property OrderExtPay $orderExtPay
 * @property OrderExtPop $orderExtPop
 * @property OrderExtStatus $orderExtStatus
 * @property OrderExtWorker $orderExtWorker
 */
class Order extends ActiveRecord
{

    const ORDER_PAY_TYPE_OFF_LINE = 1;
    const ORDER_PAY_TYPE_ON_LINE = 2;
    const ORDER_PAY_TYPE_POP = 3;
    public $order_before_status_dict_id;
    public $order_before_status_name;
    public $order_status_dict_id;
    public $order_status_name;
    public $order_flag_send;
    public $order_flag_urgent;
    public $order_flag_exception;
    public $order_flag_sys_assign;
    public $order_flag_lock;
    public $order_pop_order_code;
    public $order_pop_group_buy_code;
    public $order_pop_operation_money;
    public $order_pop_order_money;
    public $order_pop_pay_money;
    public $customer_id;
    public $order_customer_phone;
    public $order_customer_need;
    public $order_customer_memo;
    public $comment_id;
    public $invoice_id;
    public $order_customer_hidden;
    public $order_pay_type;
    public $pay_channel_id;
    public $order_pay_channel_name;
    public $order_pay_flow_num;
    public $order_pay_money;
    public $order_use_acc_balance;
    public $card_id;
    public $order_use_card_money;
    public $coupon_id;
    public $order_use_coupon_money;
    public $promotion_id;
    public $order_use_promotion_money;
    public $worker_id;
    public $worker_type_id;
    public $order_worker_type_name;
    public $order_worker_assign_type;
    public $shop_id;
    public $admin_id;
    public $attributesExt = [
        'order_before_status_dict_id',
        'order_before_status_name',
        'order_status_dict_id',
        'order_status_name',
        'order_flag_send',
        'order_flag_urgent',
        'order_flag_exception',
        'order_flag_sys_assign',
        'order_flag_lock',
        'order_pop_order_code',
        'order_pop_group_buy_code',
        'order_pop_operation_money',
        'order_pop_order_money',
        'order_pop_pay_money',
        'customer_id',
        'order_customer_phone',
        'order_customer_need',
        'order_customer_memo',
        'comment_id',
        'invoice_id',
        'order_customer_hidden',
        'order_pay_type',
        'pay_channel_id',
        'order_pay_channel_name',
        'order_pay_flow_num',
        'order_pay_money',
        'order_use_acc_balance',
        'card_id',
        'order_use_card_money',
        'coupon_id',
        'order_use_coupon_money',
        'promotion_id',
        'order_use_promotion_money',
        'worker_id',
        'worker_type_id',
        'order_worker_type_name',
        'order_worker_assign_type',
        'shop_id',
        'admin_id'
    ];

    public function attributes(){
        return array_merge(parent::attributes(),$this->attributesExt);
    }
    /**
     * @inheritdoc
     */
    public function optimisticLock()
    {
        return 'ver';
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['admin_id','order_service_type_id','order_src_id','order_booked_begin_time','address_id'],'required'],
            [['order_parent_id', 'order_is_parent', 'created_at', 'updated_at', 'isdel', 'order_ip', 'order_service_type_id', 'order_src_id', 'channel_id', 'order_booked_count', 'order_booked_begin_time', 'order_booked_end_time', 'address_id', 'order_booked_worker_id', 'checking_id'], 'integer'],
            [['order_unit_money', 'order_money'], 'number'],
            [['order_code', 'order_channel_name'], 'string', 'max' => 64],
            [['order_service_type_name', 'order_src_name'], 'string', 'max' => 128],
            [['order_address', 'order_cs_memo'], 'string', 'max' => 255],
            [['order_code'], 'unique'],
            [$this->attributesExt,'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '编号',
            'order_code' => '订单号',
            'order_parent_id' => '父级id',
            'order_is_parent' => '有无子订单 1有 0无',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
            'isdel' => '是否已删除',
            'order_ip' => '下单IP',
            'order_service_type_id' => '订单服务类别ID',
            'order_service_type_name' => '订单服务类别',
            'order_src_id' => '订单来源，订单入口id',
            'order_src_name' => '订单来源，订单入口名称',
            'channel_id' => '订单渠道ID',
            'order_channel_name' => '订单渠道名称',
            'order_unit_money' => '订单单位价格',
            'order_money' => '订单金额',
            'order_booked_count' => '预约服务数量（时长）',
            'order_booked_begin_time' => '预约开始时间',
            'order_booked_end_time' => '预约结束时间',
            'address_id' => '地址ID',
            'order_address' => '详细地址 包括 联系人 手机号',
            'order_booked_worker_id' => '指定阿姨',
            'checking_id' => '对账id',
            'order_cs_memo' => '客服备注',

            'order_before_status_dict_id' => '状态变更前订单状态字典ID',
            'order_before_status_name' => '状态变更前订单状态',
            'order_status_dict_id' => '订单状态字典ID',
            'order_status_name' => '订单状态',
            'order_flag_send' => '指派不了 0可指派 1客服指派不了 2小家政指派不了 3都指派不了',
            'order_flag_urgent' => '加急',
            'order_flag_exception' => '异常 1无经纬度',
            'order_flag_sys_assign' => '是否需要系统指派 1是 0否',
            'order_flag_lock' => '是否锁定 1锁定 0未锁定',
            'order_pop_order_code' => '第三方订单编号',
            'order_pop_group_buy_code' => '第三方团购码',
            'order_pop_operation_money' => '第三方运营费',
            'order_pop_order_money' => '第三方订单金额',
            'order_pop_pay_money' => '合作方结算金额 负数表示合作方结算规则不规律无法计算该值。',
            'customer_id' => '客户ID',
            'order_customer_phone' => '客户手机号',
            'order_customer_need' => '客户需求',
            'order_customer_memo' => '客户备注',
            'comment_id' => '评价id',
            'invoice_id' => '发票id',
            'order_customer_hidden' => '客户端是否已删除',
            'order_pay_type' => '支付方式 0未支付 1现金支付 2线上支付 3第三方预付 ',
            'pay_channel_id' => '支付渠道id',
            'order_pay_channel_name' => '支付渠道名称',
            'order_pay_flow_num' => '支付流水号',
            'order_pay_money' => '支付金额',
            'order_use_acc_balance' => '使用余额',
            'card_id' => '服务卡ID',
            'order_use_card_money' => '使用服务卡金额',
            'coupon_id' => '优惠券ID',
            'order_use_coupon_money' => '使用优惠卷金额',
            'promotion_id' => '促销id',
            'order_use_promotion_money' => '使用促销金额',
            'worker_id' => '工人id',
            'worker_type_id' => '工人职位类型ID',
            'order_worker_type_name' => '工人职位类型',
            'order_worker_assign_type' => '工人接单方式 0未接单 1工人抢单 2客服指派 3门店指派',
            'shop_id' => '工人所属门店id',
            'admin_id' => '操作人id  0客户操作 1系统操作',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderExtCustomer()
    {
        return $this->hasOne(OrderExtCustomer::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderExtFlag()
    {
        return $this->hasOne(OrderExtFlag::className(), ['order_id' => 'id'])->from(OrderExtFlag::tableName().' AS orderExtFlag');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderExtPay()
    {
        return $this->hasOne(OrderExtPay::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderExtPop()
    {
        return $this->hasOne(OrderExtPop::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderExtStatus()
    {
        return $this->hasOne(OrderExtStatus::className(), ['order_id' => 'id'])->from(OrderExtStatus::tableName().' AS orderExtStatus');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderExtWorker()
    {
        return $this->hasOne(OrderExtWorker::className(), ['order_id' => 'id']);
    }



    /**
     * 保存订单
     * 保存时记录订单历史
     * @param array $save_models 需要保存操作的类名
     * @return bool
     */
    public function doSave($save_models = ['OrderExtCustomer','OrderExtFlag','OrderExtPay','OrderExtPop','OrderExtStatus','OrderExtWorker','OrderStatusHistory'])
    {
        $transaction = static::getDb()->beginTransaction(); //开启一个事务
        $is_new_record = $this->isNewRecord;
        if ($this->save()) {
            //格式化数据开始
            $attributes = $this->attributes;
            foreach ($attributes as $k => $v) {
                $attributes[$k] = ($v === null) ? '' : $v;
            }
            $attributes['order_id'] = $attributes['id'];
            $attributes['order_created_at'] = $attributes['created_at'];
            $attributes['order_isdel'] = $attributes['isdel'];
            unset($attributes['id']);
            unset($attributes['created_at']);
            unset($attributes['updated_at']);
            unset($attributes['isdel']);
            //格式化数据结束

            //各类执行保存操作
            foreach($save_models as $modelClassName){
                $class = '\common\models\\'.$modelClassName;
                if($is_new_record || $modelClassName=='OrderStatusHistory'){//状态历史只新建不更新
                    $$modelClassName = new $class();
                    $$modelClassName->order_id = $attributes['order_id'];
                }else{
                    $$modelClassName = $class::findOne($attributes['order_id']);
                }

                $$modelClassName->setAttributes($attributes);

                if (!$$modelClassName->save()) {
                    $transaction->rollBack();//插入不成功就回滚事务
                    $this->addErrors($$modelClassName->errors);
                    return false;
                }

            }
            $OrderHistory = new OrderHistory(); //订单历史记录每次都需要插入
            $orderExtStatus = OrderExtStatus::findOne($this->id);
            $orderExtFlag = OrderExtFlag::findOne($this->id);
            $orderExtPop = OrderExtPop::findOne($this->id);
            $orderExtCustomer = OrderExtCustomer::findOne($this->id);
            $orderExtPay = OrderExtPay::findOne($this->id);
            $orderExtWorker = OrderExtWorker::findOne($this->id);
            $OrderHistory->setAttributes([
                'order_id' => $this->id,
                'order_code' => $this->order_code,
                'order_parent_id' => $this->order_parent_id,
                'order_is_parent' => $this->order_is_parent,
                'order_created_at' => $this->created_at,
                'order_isdel' => $this->isdel,
                'order_ver' => $this->ver,
                'order_before_status_dict_id' => $orderExtStatus->order_before_status_dict_id,
                'order_before_status_name' => $orderExtStatus->order_before_status_name,
                'order_status_dict_id' => $orderExtStatus->order_status_dict_id,
                'order_status_name' => $orderExtStatus->order_status_name,
                'order_flag_send' => $orderExtFlag->order_flag_send,
                'order_flag_urgent' => $orderExtFlag->order_flag_urgent,
                'order_flag_exception' => $orderExtFlag->order_flag_exception,
                'order_flag_sys_assign' => $orderExtFlag->order_flag_sys_assign,
                'order_flag_lock' => $orderExtFlag->order_flag_lock,
                'order_ip' => $this->order_ip,
                'order_service_type_id' => $this->order_service_type_id,
                'order_service_type_name' => $this->order_service_type_name,
                'order_src_id' => $this->order_src_id,
                'order_src_name' => $this->order_src_name,
                'channel_id' => $this->channel_id,
                'order_channel_name' => $this->order_channel_name,
                'order_unit_money' => $this->order_unit_money,
                'order_money' => $this->order_money,
                'order_booked_count' => $this->order_booked_count,
                'order_booked_begin_time' => $this->order_booked_begin_time,
                'order_booked_end_time' => $this->order_booked_end_time,
                'address_id' => $this->address_id,
                'order_address' => $this->order_address,
                'order_booked_worker_id' => $this->order_booked_worker_id,
                'order_pop_order_code' => $orderExtPop->order_pop_order_code,
                'order_pop_group_buy_code' => $orderExtPop->order_pop_group_buy_code,
                'order_pop_operation_money' => $orderExtPop->order_pop_operation_money,
                'order_pop_order_money' => $orderExtPop->order_pop_order_money,
                'order_pop_pay_money' => $orderExtPop->order_pop_pay_money,
                'customer_id' => $orderExtCustomer->customer_id,
                'order_customer_phone' => $orderExtCustomer->order_customer_phone,
                'order_customer_need' => $orderExtCustomer->order_customer_need,
                'order_customer_memo' => $orderExtCustomer->order_customer_memo,
                'comment_id' => $orderExtCustomer->comment_id,
                'invoice_id' => $orderExtCustomer->invoice_id,
                'order_customer_hidden' => $orderExtCustomer->order_customer_hidden,
                'order_pay_type' => $orderExtPay->order_pay_type,
                'pay_channel_id' => $orderExtPay->pay_channel_id,
                'order_pay_channel_name' => $orderExtPay->order_pay_channel_name,
                'order_pay_flow_num' => $orderExtPay->order_pay_flow_num,
                'order_pay_money' => $orderExtPay->order_pay_money,
                'order_use_acc_balance' => $orderExtPay->order_use_acc_balance,
                'card_id' => $orderExtPay->card_id,
                'order_use_card_money' => $orderExtPay->order_use_card_money,
                'coupon_id' => $orderExtPay->coupon_id,
                'order_use_coupon_money' => $orderExtPay->order_use_coupon_money,
                'promotion_id' => $orderExtPay->promotion_id,
                'order_use_promotion_money' => $orderExtPay->order_use_promotion_money,
                'worker_id' => $orderExtWorker->worker_id,
                'worker_type_id' => $orderExtWorker->worker_type_id,
                'order_worker_type_name' => $orderExtWorker->order_worker_type_name,
                'order_worker_assign_type' => $orderExtWorker->order_worker_assign_type,
                'shop_id' => $orderExtWorker->shop_id,
                'checking_id' => $this->checking_id,
                'order_cs_memo' => $this->order_cs_memo,
                'admin_id' => $this->admin_id,
            ]);
            if (!$OrderHistory->save()) {
                $transaction->rollBack();//插入不成功就回滚事务
                return false;
            }


            $transaction->commit();
            return true;
        }
        return false;
    }

    /**
     * @param $id
     * @return null|static
     */
    public static function findById($id)
    {
        $order = self::findOne($id);
        $orderExtStatus = OrderExtStatus::findOne($id);
        $orderExtFlag = OrderExtFlag::findOne($id);
        $orderExtPop = OrderExtPop::findOne($id);
        $orderExtCustomer = OrderExtCustomer::findOne($id);
        $orderExtPay = OrderExtPay::findOne($id);
        $orderExtWorker = OrderExtWorker::findOne($id);
        $order->setAttributes([
            'order_before_status_dict_id' => $orderExtStatus->order_before_status_dict_id,
            'order_before_status_name' => $orderExtStatus->order_before_status_name,
            'order_status_dict_id' => $orderExtStatus->order_status_dict_id,
            'order_status_name' => $orderExtStatus->order_status_name,
            'order_flag_send' => $orderExtFlag->order_flag_send,
            'order_flag_urgent' => $orderExtFlag->order_flag_urgent,
            'order_flag_exception' => $orderExtFlag->order_flag_exception,
            'order_flag_sys_assign' => $orderExtFlag->order_flag_sys_assign,
            'order_flag_lock' => $orderExtFlag->order_flag_lock,
            'order_pop_order_code' => $orderExtPop->order_pop_order_code,
            'order_pop_group_buy_code' => $orderExtPop->order_pop_group_buy_code,
            'order_pop_operation_money' => $orderExtPop->order_pop_operation_money,
            'order_pop_order_money' => $orderExtPop->order_pop_order_money,
            'order_pop_pay_money' => $orderExtPop->order_pop_pay_money,
            'customer_id' => $orderExtCustomer->customer_id,
            'order_customer_phone' => $orderExtCustomer->order_customer_phone,
            'order_customer_need' => $orderExtCustomer->order_customer_need,
            'order_customer_memo' => $orderExtCustomer->order_customer_memo,
            'comment_id' => $orderExtCustomer->comment_id,
            'invoice_id' => $orderExtCustomer->invoice_id,
            'order_customer_hidden' => $orderExtCustomer->order_customer_hidden,
            'order_pay_type' => $orderExtPay->order_pay_type,
            'pay_channel_id' => $orderExtPay->pay_channel_id,
            'order_pay_channel_name' => $orderExtPay->order_pay_channel_name,
            'order_pay_flow_num' => $orderExtPay->order_pay_flow_num,
            'order_pay_money' => $orderExtPay->order_pay_money,
            'order_use_acc_balance' => $orderExtPay->order_use_acc_balance,
            'card_id' => $orderExtPay->card_id,
            'order_use_card_money' => $orderExtPay->order_use_card_money,
            'coupon_id' => $orderExtPay->coupon_id,
            'order_use_coupon_money' => $orderExtPay->order_use_coupon_money,
            'promotion_id' => $orderExtPay->promotion_id,
            'order_use_promotion_money' => $orderExtPay->order_use_promotion_money,
            'worker_id' => $orderExtWorker->worker_id,
            'worker_type_id' => $orderExtWorker->worker_type_id,
            'order_worker_type_name' => $orderExtWorker->order_worker_type_name,
            'order_worker_assign_type' => $orderExtWorker->order_worker_assign_type,
            'shop_id' => $orderExtWorker->shop_id,
        ]);

        return $order;
    }

    public function init()
    {
        $class = get_class($this);
        if(!in_array($class,[
            'core\models\order\Order',
            'core\models\order\OrderSearch',
            'boss\models\order\Order',
            'boss\models\order\OrderSearch',
            'boss\models\AutoOrderSerach',
            'boss\models\ManualOrderSerach'
        ])){
            echo '非法调用！';
            exit(0);
        }
    }
}
