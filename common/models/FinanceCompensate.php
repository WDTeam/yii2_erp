<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%finance_compensate}}".
 *
 * @property integer $id
 * @property string $finance_compensate_oa_num
 * @property string $finance_compensate_pay_money
 * @property string $finance_compensate_cause
 * @property string $finance_compensate_tel
 * @property string $finance_compensate_money
 * @property integer $finance_pay_channel_id
 * @property string $finance_pay_channel_name
 * @property integer $finance_order_channel_id
 * @property string $finance_order_channel_name
 * @property string $finance_compensate_discount
 * @property integer $finance_compensate_pay_create_time
 * @property string $finance_compensate_pay_flow_num
 * @property integer $finance_compensate_pay_status
 * @property integer $finance_compensate_worker_id
 * @property string $finance_compensate_worker_tel
 * @property string $finance_compensate_proposer
 * @property string $finance_compensate_auditor
 * @property integer $create_time
 * @property integer $is_del
 */
class FinanceCompensate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance_compensate}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['finance_compensate_pay_money', 'finance_compensate_money', 'finance_compensate_discount'], 'number'],
            [['finance_pay_channel_id', 'finance_order_channel_id', 'finance_compensate_pay_create_time', 'finance_compensate_pay_status', 'finance_compensate_worker_id', 'create_time', 'is_del'], 'integer'],
            [['create_time'], 'required'],
            [['finance_compensate_oa_num'], 'string', 'max' => 40],
            [['finance_compensate_cause'], 'string', 'max' => 150],
            [['finance_compensate_tel', 'finance_compensate_worker_tel', 'finance_compensate_proposer', 'finance_compensate_auditor'], 'string', 'max' => 20],
            [['finance_pay_channel_name', 'finance_order_channel_name'], 'string', 'max' => 60],
            [['finance_compensate_pay_flow_num'], 'string', 'max' => 80]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '主键id'),
            'finance_compensate_oa_num' => Yii::t('app', 'OA批号'),
            'finance_compensate_pay_money' => Yii::t('app', '支付金额'),
            'finance_compensate_cause' => Yii::t('app', '赔偿原因'),
            'finance_compensate_tel' => Yii::t('app', '用户电话'),
            'finance_compensate_money' => Yii::t('app', '赔偿金额'),
            'finance_pay_channel_id' => Yii::t('app', '支付渠道id'),
            'finance_pay_channel_name' => Yii::t('app', '支付渠道名称'),
            'finance_order_channel_id' => Yii::t('app', '订单渠道id'),
            'finance_order_channel_name' => Yii::t('app', '订单渠道名称'),
            'finance_compensate_discount' => Yii::t('app', '优惠价格'),
            'finance_compensate_pay_create_time' => Yii::t('app', '订单支付时间'),
            'finance_compensate_pay_flow_num' => Yii::t('app', '订单号'),
            'finance_compensate_pay_status' => Yii::t('app', '支付状态 1支付 0 未支付 2 其他'),
            'finance_compensate_worker_id' => Yii::t('app', '服务阿姨'),
            'finance_compensate_worker_tel' => Yii::t('app', '阿姨电话'),
            'finance_compensate_proposer' => Yii::t('app', '申请人'),
            'finance_compensate_auditor' => Yii::t('app', '审核人'),
            'create_time' => Yii::t('app', '退款申请时间'),
            'is_del' => Yii::t('app', '0 正常 1删除'),
        ];
    }
}
