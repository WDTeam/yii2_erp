<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%finance_refund}}".
 *
 * @property integer $id
 * @property string $finance_refund_tel
 * @property string $finance_refund_money
 * @property integer $finance_refund_stype
 * @property string $finance_refund_reason
 * @property string $finance_refund_discount
 * @property integer $finance_refund_pay_create_time
 * @property integer $finance_pay_channel_id
 * @property string $finance_pay_channel_name
 * @property string $finance_refund_pay_flow_num
 * @property integer $finance_refund_pay_status
 * @property integer $finance_refund_worker_id
 * @property string $finance_refund_worker_tel
 * @property integer $create_time
 * @property integer $is_del
 */
class FinanceRefund extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance_refund}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['finance_refund_tel', 'finance_refund_stype', 'create_time'], 'required'],
            [['finance_refund_money', 'finance_refund_discount'], 'number'],
            [['finance_refund_stype', 'finance_refund_pay_create_time', 'finance_pay_channel_id', 'finance_refund_pay_status', 'finance_refund_worker_id', 'create_time', 'is_del'], 'integer'],
            [['finance_refund_tel', 'finance_refund_worker_tel'], 'string', 'max' => 20],
            [['finance_refund_reason'], 'string', 'max' => 255],
            [['finance_pay_channel_name', 'finance_refund_pay_flow_num'], 'string', 'max' => 80]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '主键id'),
            'finance_refund_tel' => Yii::t('app', '用户电话'),
            'finance_refund_money' => Yii::t('app', '退款金额'),
            'finance_refund_stype' => Yii::t('app', '申请方式'),
            'finance_refund_reason' => Yii::t('app', '退款理由'),
            'finance_refund_discount' => Yii::t('app', '优惠价格'),
            'finance_refund_pay_create_time' => Yii::t('app', '订单支付时间'),
            'finance_pay_channel_id' => Yii::t('app', '支付方式id'),
            'finance_pay_channel_name' => Yii::t('app', '支付方式名称'),
            'finance_refund_pay_flow_num' => Yii::t('app', '订单号'),
            'finance_refund_pay_status' => Yii::t('app', '支付状态 1支付 0 未支付 2 其他'),
            'finance_refund_worker_id' => Yii::t('app', '服务阿姨'),
            'finance_refund_worker_tel' => Yii::t('app', '阿姨电话'),
            'create_time' => Yii::t('app', '退款申请时间'),
            'is_del' => Yii::t('app', '0 正常 1删除'),
        ];
    }
}
