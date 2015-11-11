<?php

namespace dbbase\models\finance;

use Yii;

/**
 * This is the model class for table "{{%finance_compensate}}".
 *
 * @property integer $id
 * @property string $finance_compensate_code
 * @property string $finance_compensate_oa_code
 * @property integer $finance_complaint_id
 * @property string $finance_complaint_code
 * @property integer $order_id
 * @property string $order_code
 * @property integer $worker_id
 * @property integer $customer_id
 * @property string $finance_compensate_coupon 
 * @property string $finance_compensate_coupon_money
 * @property string $finance_compensate_money
 * @property string $finance_compensate_total_money
 * @property string $finance_compensate_insurance_money
 * @property string $finance_compensate_company_money
 * @property string $finance_compensate_worker_money
 * @property string $finance_compensate_reason
 * @property string $finance_compensate_proposer
 * @property string $finance_compensate_auditor
 * @property integer $finance_compensate_status
 * @property string $comment
 * @property integer $updated_at
 * @property integer $created_at
 * @property integer $is_softdel
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
            [['finance_complaint_id', 'worker_id', 'customer_id', 'updated_at', 'created_at', 'is_softdel','finance_compensate_status'], 'integer'],
            [['finance_compensate_money','finance_compensate_total_money','finance_compensate_insurance_money','finance_compensate_company_money','finance_compensate_worker_money'], 'number'],
            [['finance_compensate_reason', 'comment','worker_tel','worker_name','customer_name'], 'string'],
            [['finance_compensate_oa_code'], 'string', 'max' => 40],
            [['finance_compensate_coupon','finance_compensate_coupon_money'], 'string', 'max' => 150],
            [['finance_compensate_proposer', 'finance_compensate_auditor'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '主键id'),
            'finance_compensate_code' => Yii::t('app', '赔偿编号'),
            'finance_compensate_oa_code' => Yii::t('app', 'OA批号'),
            'finance_complaint_id' => Yii::t('app', '投诉id'),
            'finance_complaint_code' => Yii::t('app', '投诉编号'),
            'order_id' => Yii::t('app', '订单id'),
            'order_code' => Yii::t('app', '订单编号'),
            'worker_id' => Yii::t('app', '阿姨Id'),
            'worker_tel' => Yii::t('app', '阿姨电话'),
            'worker_name' => Yii::t('app', '阿姨姓名'),
            'customer_id' => Yii::t('app', '客户Id'),
            'customer_name' => Yii::t('app', '客户姓名'),
            'finance_compensate_coupon' => Yii::t('app', ' 优惠券'),
            'finance_compensate_coupon_money' => Yii::t('app', ' 优惠券金额'),
            'finance_compensate_money' => Yii::t('app', ' 赔偿金额'),
            'finance_compensate_total_money' => Yii::t('app', ' 赔偿总金额'),
            'finance_compensate_insurance_money' => Yii::t('app', ' 保险理赔金额'),
            'finance_compensate_company_money' => Yii::t('app', ' 公司理赔金额'),
            'finance_compensate_worker_money' => Yii::t('app', ' 阿姨赔付金额'),
            'finance_compensate_reason' => Yii::t('app', '赔偿原因'),
            'finance_compensate_proposer' => Yii::t('app', '赔偿申请人'),
            'finance_compensate_auditor' => Yii::t('app', '审核人'),
            'finance_compensate_status' => Yii::t('app', '审核状态'),
            'comment' => Yii::t('app', '备注'),
            'updated_at' => Yii::t('app', '审核时间'),
            'created_at' => Yii::t('app', '申请时间'),
            'is_softdel' => Yii::t('app', '0 正常 1删除'),
        ];
    }
}
