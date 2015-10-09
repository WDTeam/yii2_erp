<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%finance_invoice}}".
 *
 * @property integer $id
 * @property integer $finance_invoice_serial_number
 * @property string $finance_invoice_customer_tel
 * @property string $finance_invoice_worker_tel
 * @property integer $pay_channel_pay_id
 * @property string $pay_channel_pay_title
 * @property integer $finance_invoice_pay_status
 * @property integer $admin_confirm_uid
 * @property integer $finance_invoice_enrolment_time
 * @property string $finance_invoice_money
 * @property string $finance_invoice_title
 * @property string $finance_invoice_address
 * @property integer $finance_invoice_status
 * @property integer $finance_invoice_check_id
 * @property integer $finance_invoice_number
 * @property string $finance_invoice_service_money
 * @property string $finance_invoice_corp_email
 * @property string $finance_invoice_corp_address
 * @property string $finance_invoice_corp_name
 * @property integer $finance_invoice_district_id
 * @property integer $classify_id
 * @property integer $classify_title
 * @property string $create_time
 * @property integer $is_del
 */
class FinanceInvoice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance_invoice}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['finance_invoice_serial_number', 'pay_channel_pay_id', 'finance_invoice_pay_status', 'admin_confirm_uid', 'finance_invoice_enrolment_time', 'finance_invoice_status', 'finance_invoice_check_id', 'finance_invoice_number', 'finance_invoice_district_id', 'classify_id', 'classify_title', 'is_del'], 'integer'],
            [['finance_invoice_money', 'finance_invoice_service_money'], 'number'],
            [['classify_id', 'classify_title', 'create_time'], 'required'],
            [['finance_invoice_customer_tel', 'finance_invoice_worker_tel'], 'string', 'max' => 20],
            [['pay_channel_pay_title', 'finance_invoice_address', 'finance_invoice_corp_address'], 'string', 'max' => 200],
            [['finance_invoice_title'], 'string', 'max' => 100],
            [['finance_invoice_corp_email'], 'string', 'max' => 40],
            [['finance_invoice_corp_name'], 'string', 'max' => 150],
            [['create_time'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键id',
            'finance_invoice_serial_number' => '流水号',
            'finance_invoice_customer_tel' => '用户电话',
            'finance_invoice_worker_tel' => '阿姨电话',
            'pay_channel_pay_id' => '支付方式',
            'pay_channel_pay_title' => '支付名称',
            'finance_invoice_pay_status' => '支付状态',
            'admin_confirm_uid' => '确认人',
            'finance_invoice_enrolment_time' => '申请时间',
            'finance_invoice_money' => '发票金额',
            'finance_invoice_title' => '发票抬头',
            'finance_invoice_address' => '邮寄地址',
            'finance_invoice_status' => '0 未开发票 1已邮寄 2 未邮寄  3 上门取  4 审核中 5 审核通过 6已完成 7 已退回',
            'finance_invoice_check_id' => '审核人id',
            'finance_invoice_number' => '发票数量',
            'finance_invoice_service_money' => '开发票服务费',
            'finance_invoice_corp_email' => '邮箱',
            'finance_invoice_corp_address' => '公司地址',
            'finance_invoice_corp_name' => '公司名称',
            'finance_invoice_district_id' => '城市id',
            'classify_id' => '业务id',
            'classify_title' => '业务title',
            'create_time' => '增加时间',
            'is_del' => '0 正常 1 删除',
        ];
    }
}
