<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%finance_compensate}}".
 *
 * @property integer $id
 * @property string $finance_compensate_oa_code
 * @property integer $finance_complaint_id
 * @property integer $worker_id
 * @property integer $customer_id
 * @property string $finance_compensate_coupon
 * @property string $finance_compensate_money
 * @property string $finance_compensate_reason
 * @property string $finance_compensate_proposer
 * @property string $finance_compensate_auditor
 * @property string $comment
 * @property integer $updated_at
 * @property integer $created_at
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
            [['finance_complaint_id', 'worker_id', 'customer_id', 'updated_at', 'created_at', 'is_del'], 'integer'],
            [['finance_compensate_money'], 'number'],
            [['finance_compensate_reason', 'comment'], 'string'],
            [['finance_compensate_oa_code'], 'string', 'max' => 40],
            [['finance_compensate_coupon'], 'string', 'max' => 150],
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
            'finance_compensate_oa_code' => Yii::t('app', 'OA批号'),
            'finance_complaint_id' => Yii::t('app', '投诉Id'),
            'worker_id' => Yii::t('app', '阿姨Id'),
            'customer_id' => Yii::t('app', '阿姨Id'),
            'finance_compensate_coupon' => Yii::t('app', ' 优惠券,可能是多个优惠券，用分号分隔'),
            'finance_compensate_money' => Yii::t('app', ' 赔偿金额'),
            'finance_compensate_reason' => Yii::t('app', '赔偿原因'),
            'finance_compensate_proposer' => Yii::t('app', '申请人'),
            'finance_compensate_auditor' => Yii::t('app', '审核人'),
            'comment' => Yii::t('app', '备注，可能是未通过原因'),
            'updated_at' => Yii::t('app', '审核时间'),
            'created_at' => Yii::t('app', '申请时间'),
            'is_del' => Yii::t('app', '0 正常 1删除'),
        ];
    }
}
