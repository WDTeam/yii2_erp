<?php

namespace dbbase\models\finance;

use Yii;

/**
 * This is the model class for table "{{%finance_settle_apply_approval_log}}".
 *
 * @property integer $id
 * @property string $finance_settle_apply_id
 * @property integer $finance_settle_apply_code
 * @property integer $finance_settle_apply_reviewer_id
 * @property integer $finance_settle_apply_reviewer
 * @property integer $finance_settle_apply_node_id
 * @property integer $finance_settle_apply_node_des
 * @property integer $finance_settle_apply_is_passed
 * @property string $finance_settle_apply_reviewer_comment
 * @property integer $is_softdel
 * @property integer $updated_at
 * @property integer $created_at
 */
class FinanceSettleApplyApprovalLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance_settle_apply_approval_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['finance_settle_apply_id', 'finance_settle_apply_reviewer_id', 'finance_settle_apply_reviewer', 'finance_settle_apply_node_id', 'finance_settle_apply_node_des', 'finance_settle_apply_is_passed', 'is_softdel', 'updated_at', 'created_at'], 'integer'],
            [['finance_settle_apply_reviewer_comment'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '主键'),
            'finance_settle_apply_id' => Yii::t('app', '结算申请id'),
            'finance_settle_apply_code' => Yii::t('app', '结算编号，可能是阿姨结算编号，也可能是门店结算编号'),
            'finance_settle_apply_reviewer_id' => Yii::t('app', '审核人员Id'),
            'finance_settle_apply_reviewer' => Yii::t('app', '审核人员姓名'),
            'finance_settle_apply_node_id' => Yii::t('app', '审核节点id'),
            'finance_settle_apply_node_des' => Yii::t('app', '审核描述'),
            'finance_settle_apply_is_passed' => Yii::t('app', '审核是否通过，0审核未通过，1审核通过'),
            'finance_settle_apply_reviewer_comment' => Yii::t('app', '审核人员意见'),
            'is_softdel' => Yii::t('app', '是否被删除，0为启用，1为删除'),
            'updated_at' => Yii::t('app', '更新时间'),
            'created_at' => Yii::t('app', '创建时间'),
        ];
    }
}
