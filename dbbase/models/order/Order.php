<?php

namespace dbbase\models\order;

use Yii;
use dbbase\models\ActiveRecord;
/**
 * This is the model class for table "{{%order}}".
 *
 * @property string $id
 * @property string $order_code
 * @property string $order_batch_code
 * @property string $order_parent_id
 * @property integer $order_is_parent
 * @property string $created_at
 * @property string $updated_at
 * @property integer $isdel
 * @property integer $version
 * @property string $order_ip
 * @property integer $order_service_type_id
 * @property string $order_service_type_name
 * @property integer $order_service_item_id
 * @property string $order_service_item_name
 * @property integer $order_channel_type_id
 * @property string $order_channel_type_name
 * @property string $channel_id
 * @property string $order_channel_name
 * @property string $order_unit_money
 * @property string $order_money
 * @property string $order_booked_count
 * @property string $order_booked_begin_time
 * @property string $order_booked_end_time
 * @property string $address_id
 * @property string $order_lat
 * @property string $order_lng
 * @property string $district_id
 * @property string $city_id
 * @property string $order_address
 * @property string $order_booked_worker_id
 * @property string $order_cs_memo
 * @property string $order_sys_memo
 * @property string $order_cancel_cause_id
 * @property string $order_cancel_cause_detail
 * @property string $order_cancel_cause_memo
 * @property string $order_checked_code
 * @property string $order_worker_payoff_code
 * @property string $order_refund_code
 * @property string $order_complaint_code
 * @property string $order_compensate_code
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

    public $order_before_status_dict_id;
    public $order_before_status_name;
    public $order_status_dict_id;
    public $order_status_name;
    public $order_status_boss;
    public $order_status_customer;
    public $order_status_worker;
    public $order_flag_send;
    public $order_flag_urgent;
    public $order_flag_exception;
    public $order_flag_sys_assign;
    public $order_flag_lock;
    public $order_flag_lock_time;
    public $order_flag_worker_sms;
    public $order_flag_worker_jpush;
    public $order_flag_worker_ivr;
    public $order_flag_change_booked_worker;
    public $order_flag_is_checked;
    public $order_pop_order_code;
    public $order_pop_group_buy_code;
    public $order_pop_operation_money;
    public $order_pop_order_money;
    public $order_pop_pay_money;
    public $customer_id;
    public $order_customer_phone;
    public $order_customer_is_vip;
    public $order_customer_need;
    public $order_customer_memo;
    public $comment_id;
    public $invoice_id;
    public $order_customer_hidden;
    public $pay_channel_type_id;
    public $order_pay_channel_type_name;
    public $pay_channel_id;
    public $order_pay_channel_name;
    public $order_pay_flow_num;
    public $order_pay_code;
    public $order_balance_code;
    public $order_pay_money;
    public $order_use_acc_balance;
    public $card_id;
    public $order_use_card_money;
    public $coupon_id;
    public $order_coupon_code;
    public $order_use_coupon_money;
    public $promotion_id;
    public $order_use_promotion_money;
    public $worker_id;
    public $order_worker_phone;
    public $order_worker_name;
    public $order_worker_memo;
    public $worker_type_id;
    public $order_worker_type_name;
    public $order_worker_assign_type;
    public $order_worker_assign_time;
    public $shop_id;
    public $order_worker_shop_name;
    public $admin_id;
    public $order_is_use_balance;

    public $attributesExt = [
        'order_before_status_dict_id',
        'order_before_status_name',
        'order_status_dict_id',
        'order_status_name',
        'order_status_boss',
        'order_status_customer',
        'order_status_worker',
        'order_flag_send',
        'order_flag_urgent',
        'order_flag_exception',
        'order_flag_sys_assign',
        'order_flag_lock',
        'order_flag_lock_time',
        'order_flag_worker_sms',
        'order_flag_worker_jpush',
        'order_flag_worker_ivr',
        'order_flag_change_booked_worker',
        'order_flag_is_checked',
        'order_pop_order_code',
        'order_pop_group_buy_code',
        'order_pop_operation_money',
        'order_pop_order_money',
        'order_pop_pay_money',
        'customer_id',
        'order_customer_phone',
        'order_customer_is_vip',
        'order_customer_need',
        'order_customer_memo',
        'comment_id',
        'invoice_id',
        'order_customer_hidden',
        'pay_channel_type_id',
        'order_pay_channel_type_name',
        'pay_channel_id',
        'order_pay_channel_name',
        'order_pay_flow_num',
        'order_pay_code',
        'order_balance_code',
        'order_pay_money',
        'order_use_acc_balance',
        'card_id',
        'order_use_card_money',
        'coupon_id',
        'order_coupon_code',
        'order_use_coupon_money',
        'promotion_id',
        'order_use_promotion_money',
        'worker_id',
        'order_worker_phone',
        'order_worker_name',
        'order_worker_memo',
        'worker_type_id',
        'order_worker_type_name',
        'order_worker_assign_type',
        'order_worker_assign_time',
        'shop_id',
        'order_worker_shop_name',
        'admin_id',
        'order_is_use_balance'
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
            [['admin_id','order_service_type_id','order_service_item_id','order_channel_type_id','order_booked_begin_time','address_id','order_unit_money','order_code', 'order_booked_count','order_money'],'required'],

            [['order_parent_id', 'order_is_parent', 'created_at', 'updated_at', 'isdel', 'order_service_type_id','order_service_item_id',
                'order_channel_type_id', 'channel_id', 'order_booked_begin_time', 'order_booked_end_time', 'order_cancel_cause_id',
                'city_id', 'address_id', 'district_id', 'order_booked_worker_id','version'], 'integer'],

            [['order_unit_money',  'order_booked_count','order_money','order_lat','order_lng'], 'number'],
            [['order_code', 'order_channel_name', 'order_batch_code','order_checked_code','order_worker_payoff_code','order_complaint_code','order_complaint_code','order_compensate_code'], 'string', 'max' => 64],
            [['order_service_type_name','order_service_item_name', 'order_ip','order_channel_type_name'], 'string', 'max' => 128],
            [['order_address', 'order_cs_memo','order_sys_memo','order_cancel_cause_detail','order_cancel_cause_memo'], 'string', 'max' => 255],
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
            'order_batch_code' => '周期订单号',
            'order_checked_code' => '对账单号',
            'order_refund_code' => '退款单号',
            'order_complaint_code' => '投诉单号',
            'order_compensate_code' => '赔偿单号',
            'order_worker_payoff_code' => '阿姨结算单号',
            'order_parent_id' => '父级id',
            'order_is_parent' => '有无子订单 1有 0无',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
            'isdel' => '是否已删除',
            'order_ip' => '下单IP',
            'order_service_type_id' => '服务类别',
            'order_service_type_name' => '服务类别',
            'order_service_item_id' => '服务项目',
            'order_service_item_name' => '服务项目',
            'order_channel_type_id' => '订单来源，订单入口id',
            'order_channel_type_name' => '订单来源，订单入口名称',
            'channel_id' => '订单渠道ID',
            'order_channel_name' => '订单渠道名称',
            'order_unit_money' => '订单单位价格',
            'order_money' => '订单金额',
            'order_booked_count' => '预约服务数量（时长）',
            'order_booked_begin_time' => '预约开始时间',
            'order_booked_end_time' => '预约结束时间',
            'city_id' => '城市ID',
            'address_id' => '地址ID',
            'district_id' => '商圈ID',
            'order_address' => '详细地址 包括 联系人 手机号',
            'order_lat' => '纬度',
            'order_lng' => '经度',
            'order_booked_worker_id' => '指定阿姨',
            'order_cs_memo' => '客服备注',
            'order_sys_memo' => '系统备注',
            'order_cancel_cause_id' => '取消原因id',
            'order_cancel_cause_detail' => '取消原因',
            'order_cancel_cause_memo' => '取消备注',

            'order_before_status_dict_id' => '状态变更前订单状态字典ID',
            'order_before_status_name' => '状态变更前订单状态',
            'order_status_dict_id' => '订单状态字典ID',
            'order_status_name' => '订单状态',
            'order_status_boss' => 'BOSS状态名称',
            'order_status_customer' => '客户端状态名称',
            'order_status_worker' => '阿姨端状态名称',
            'order_flag_send' => '指派不了 0可指派 1客服指派不了 2小家政指派不了 3都指派不了',
            'order_flag_urgent' => '加急',
            'order_flag_exception' => '异常 1无经纬度',
            'order_flag_sys_assign' => '是否需要系统指派 1是 0否',
            'order_flag_lock' => '是否锁定 1锁定 0未锁定',
            'order_flag_lock_time' => '加锁时间',
            'order_flag_worker_sms' => '是否给阿姨发过短信',
            'order_flag_worker_jpush' => '是否给阿姨发过极光',
            'order_flag_worker_ivr' => '是否给阿姨发过ivr',
            'order_flag_change_booked_worker' => '是否可更换指定阿姨',
            'order_flag_is_checked' => '是否已对账',
            'order_pop_order_code' => '第三方订单编号',
            'order_pop_group_buy_code' => '第三方团购码',
            'order_pop_operation_money' => '第三方运营费',
            'order_pop_order_money' => '第三方订单金额',
            'order_pop_pay_money' => '合作方结算金额 负数表示合作方结算规则不规律无法计算该值。',
            'customer_id' => '客户ID',
            'order_customer_phone' => '客户手机号',
            'order_customer_is_vip' => '是否是会员',
            'order_customer_need' => '客户需求',
            'order_customer_memo' => '客户备注',
            'comment_id' => '评价id',
            'invoice_id' => '发票id',
            'order_customer_hidden' => '客户端是否已删除',
            'pay_channel_type_id' => '支付渠道分类id',
            'order_pay_channel_type_name' => '支付渠道分类名称',
            'pay_channel_id' => '支付渠道id',
            'order_pay_channel_name' => '支付渠道名称',
            'order_pay_flow_num' => '支付流水号',
            'order_pay_code' => '支付单号',
            'order_balance_code' => '支付余额单号',
            'order_pay_money' => '支付金额',
            'order_use_acc_balance' => '使用余额',
            'card_id' => '服务卡ID',
            'order_use_card_money' => '使用服务卡金额',
            'coupon_id' => '优惠券ID',
            'order_coupon_code' => '优惠码',
            'order_use_coupon_money' => '使用优惠卷金额',
            'promotion_id' => '促销id',
            'order_use_promotion_money' => '使用促销金额',
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
            'admin_id' => '操作人id 1系统 2客户 3阿姨 >3后台管理员',
            'order_is_use_balance'=>'是否使用余额 1是 0否',
            'created_at_end'=>'结束时间',
            'stypechannel'=>'渠道分类'   
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderExtCustomer()
    {
        return $this->hasOne(OrderExtCustomer::className(), ['order_id' => 'id'])->from(OrderExtCustomer::tableName().' AS orderExtCustomer');
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
        return $this->hasOne(OrderExtPay::className(), ['order_id' => 'id'])->from(OrderExtPay::tableName().' AS orderExtPay');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderExtPop()
    {
        return $this->hasOne(OrderExtPop::className(), ['order_id' => 'id'])->from(OrderExtPop::tableName().' AS orderExtPop');
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
        return $this->hasOne(OrderExtWorker::className(), ['order_id' => 'id'])->from(OrderExtWorker::tableName().' AS orderExtWorker');
    }

    public function getOrderStatusHistory()
    {
        return $this->hasMany(OrderStatusHistory::className(),['order_id'=>'id']);
    }

    /**
     * 保存订单
     * 保存时记录订单历史
     * @param array $save_models 需要保存操作的类名
     * @param $transact 事务
     * @return bool
     */
    public function doSave($save_models = ['OrderExtCustomer','OrderExtFlag','OrderExtPay','OrderExtPop','OrderExtStatus','OrderExtWorker','OrderStatusHistory'],$transact = null)
    {
        $order_transaction = empty($transact)?static::getDb()->beginTransaction():$transact; //开启一个事务
        $is_new_record = $this->isNewRecord;
        if(!$this->isNewRecord)$this->version++;

        if ($this->save()) {
            //格式化数据开始
            $attributes = $this->attributes;
            foreach ($attributes as $k => $v) {
                if($v === null){
                    unset($attributes[$k]);
                }
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
                $class = '\dbbase\models\order\\'.$modelClassName;
                if($is_new_record || $modelClassName=='OrderStatusHistory'){//状态历史只新建不更新
                    $$modelClassName = new $class();
                    $$modelClassName->order_id = $attributes['order_id'];
                }else{
                    $$modelClassName = $class::findOne($attributes['order_id']);
                }
                $$modelClassName->setAttributes($attributes);

                if (!$$modelClassName->save()) {
                    $order_transaction->rollBack();//插入不成功就回滚事务
                    $this->addErrors($$modelClassName->errors);
                    return false;
                }

            }
            $OrderHistory = new OrderHistory(); //订单历史记录每次都需要插入
            $modelClassNames = ['OrderExtCustomer','OrderExtFlag','OrderExtPay','OrderExtPop','OrderExtStatus','OrderExtWorker'];
            foreach($modelClassNames as $modelClassName) {
                $class = '\dbbase\models\order\\'.$modelClassName;
                $instance = $class::findOne($this->id);
                $attributes = $instance->attributes;
                unset($attributes['order_id']);
                unset($attributes['created_at']);
                unset($attributes['updated_at']);
                if (isset($attributes['isdel'])) {
                    unset($attributes['isdel']);
                }
                $OrderHistory->setAttributes($attributes);
            }
            $OrderHistory->setAttributes([
                'order_id' => $this->id,
                'order_code' => $this->order_code,
                'order_batch_code' => $this->order_batch_code,
                'order_checked_code' => $this->order_checked_code,
                'order_refund_code' => $this->order_refund_code,
                'order_complaint_code' => $this->order_complaint_code,
                'order_compensate_code' => $this->order_compensate_code,
                'order_worker_payoff_code' => $this->order_worker_payoff_code,
                'order_parent_id' => $this->order_parent_id,
                'order_is_parent' => $this->order_is_parent,
                'order_created_at' => $this->created_at,
                'order_isdel' => $this->isdel,
                'order_ver' => $this->version,
                'order_ip' => $this->order_ip,
                'order_service_type_id' => $this->order_service_type_id,
                'order_service_type_name' => $this->order_service_type_name,
                'order_service_item_id' => $this->order_service_item_id,
                'order_service_item_name' => $this->order_service_item_name,
                'order_channel_type_id' => $this->order_channel_type_id,
                'order_channel_type_name' => $this->order_channel_type_name,
                'channel_id' => $this->channel_id,
                'order_channel_name' => $this->order_channel_name,
                'order_unit_money' => $this->order_unit_money,
                'order_money' => $this->order_money,
                'order_booked_count' => $this->order_booked_count,
                'order_booked_begin_time' => $this->order_booked_begin_time,
                'order_booked_end_time' => $this->order_booked_end_time,
                'city_id' => $this->city_id,
                'district_id' => $this->district_id,
                'address_id' => $this->address_id,
                'order_address' => $this->order_address,
                'order_lat' => $this->order_lat,
                'order_lng' => $this->order_lng,
                'order_booked_worker_id' => $this->order_booked_worker_id,
                'order_cs_memo' => $this->order_cs_memo,
                'order_sys_memo' => $this->order_sys_memo,
                'order_cancel_cause_id' => $this->order_cancel_cause_id,
                'order_cancel_cause_detail' => $this->order_cancel_cause_detail,
                'order_cancel_cause_memo' => $this->order_cancel_cause_memo,
                'admin_id' => $this->admin_id,
            ]);

            if (!$OrderHistory->save()) {
                $order_transaction->rollBack();//插入不成功就回滚事务
                return false;
            }


            if(empty($transact)){
                $order_transaction->commit();
            }
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
        $modelClassNames = ['OrderExtCustomer','OrderExtFlag','OrderExtPay','OrderExtPop','OrderExtStatus','OrderExtWorker'];
        foreach($modelClassNames as $modelClassName) {
            $class = '\dbbase\models\order\\'.$modelClassName;
            $instance = $class::findOne($id);
            $attributes = $instance->attributes;
            unset($attributes['order_id']);
            unset($attributes['created_at']);
            unset($attributes['updated_at']);
            if (isset($attributes['isdel'])) {
                unset($attributes['isdel']);
            }
            $order->setAttributes($attributes);
        }
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
            'boss\models\order\OrderSearchIndex',
            'boss\models\AutoOrderSerach',
            'boss\models\ManualOrderSerach'
        ])){
            echo '非法调用！';
            exit(0);
        }
    }
}
