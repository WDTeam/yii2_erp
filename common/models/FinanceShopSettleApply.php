<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%finance_shop_settle_apply}}".
 *
 * @property integer $id
 * @property integer $shop_id
 * @property string $shop_name
 * @property integer $shop_manager_id
 * @property string $shop_manager_name
 * @property integer $finance_shop_settle_apply_order_count
 * @property string $finance_shop_settle_apply_fee_per_order
 * @property string $finance_shop_settle_apply_fee
 * @property integer $finance_shop_settle_apply_status
 * @property integer $finance_shop_settle_apply_cycle
 * @property string $finance_shop_settle_apply_cycle_des
 * @property string $finance_shop_settle_apply_reviewer
 * @property integer $finance_shop_settle_apply_starttime
 * @property integer $finance_shop_settle_apply_endtime
 * @property integer $isdel
 * @property integer $updated_at
 * @property integer $created_at
 * @property text $comment
 */
class FinanceShopSettleApply extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance_shop_settle_apply}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['shop_id', 'shop_name', 'shop_manager_id', 'shop_manager_name', 'finance_shop_settle_apply_cycle', 'finance_shop_settle_apply_cycle_des'], 'required'],
            [['shop_id', 'shop_manager_id', 'finance_shop_settle_apply_order_count', 'finance_shop_settle_apply_status', 'finance_shop_settle_apply_cycle', 'finance_shop_settle_apply_starttime', 'finance_shop_settle_apply_endtime', 'isdel', 'updated_at', 'created_at'], 'integer'],
            [['finance_shop_settle_apply_fee_per_order', 'finance_shop_settle_apply_fee'], 'number'],
            [['finance_shop_settle_apply_cycle_des'], 'string'],
            [['shop_name', 'shop_manager_name'], 'string', 'max' => 100],
            [['finance_shop_settle_apply_reviewer'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '主键'),
            'shop_id' => Yii::t('app', '门店id'),
            'shop_name' => Yii::t('app', '门店名称'),
            'shop_manager_id' => Yii::t('app', '归属家政id'),
            'shop_manager_name' => Yii::t('app', '归属家政名称'),
            'finance_shop_settle_apply_order_count' => Yii::t('app', '完成总单量'),
            'finance_shop_settle_apply_fee_per_order' => Yii::t('app', '每单管理费'),
            'finance_shop_settle_apply_fee' => Yii::t('app', '管理费'),
            'finance_shop_settle_apply_status' => Yii::t('app', '结算状态'),
            'finance_shop_settle_apply_cycle' => Yii::t('app', '结算周期，1周结，2月结'),
            'finance_shop_settle_apply_cycle_des' => Yii::t('app', '结算周期，周结，月结'),
            'finance_shop_settle_apply_reviewer' => Yii::t('app', '审核人姓名'),
            'finance_shop_settle_apply_starttime' => Yii::t('app', '结算开始时间'),
            'finance_shop_settle_apply_endtime' => Yii::t('app', '结算结束时间'),
            'isdel' => Yii::t('app', '是否被删除，0为启用，1为删除'),
            'updated_at' => Yii::t('app', '审核时间'),
            'created_at' => Yii::t('app', '申请时间'),
            'comment' => Yii::t('app', '备注'),
        ];
    }
}
