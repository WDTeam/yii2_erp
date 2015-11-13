<?php
/**
* 退款日志数据库映射关系 
* ==========================
* 北京一家洁 版权所有 2015-2018 
* ----------------------------
* 这不是一个自由软件，未经授权不许任何使用和传播。
* ==========================
* @date: 2015-11-12
* @author: peak pan 
* @version:1.0
*/

namespace dbbase\models\finance;

use Yii;

/**
 * This is the model class for table "{{%finance_record_log}}".
 *
 * @property integer $id
 * @property integer $finance_order_channel_id
 * @property string $finance_order_channel_name
 * @property integer $finance_pay_channel_id
 * @property string $finance_pay_channel_name
 * @property integer $finance_record_log_succeed_count
 * @property string $finance_record_log_succeed_sum_money
 * @property integer $finance_record_log_manual_count
 * @property string $finance_record_log_manual_sum_money
 * @property integer $finance_record_log_failure_count
 * @property string $finance_record_log_failure_money
 * @property string $finance_record_log_confirm_name
 * @property string $finance_record_log_fee
 * @property integer $create_time
 * @property integer $is_del
 */
class FinanceRecordLog extends \yii\db\ActiveRecord
{
	
	
	public $not_dispose_nub;
	public $not_dispose_sum;
			
			
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance_record_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['finance_order_channel_id', 'finance_pay_channel_id', 'finance_record_log_succeed_count', 'finance_record_log_manual_count', 'finance_record_log_failure_count', 'create_time', 'is_del'], 'integer'],
            [['finance_record_log_succeed_sum_money', 'finance_record_log_manual_sum_money', 'finance_record_log_failure_money', 'finance_record_log_fee'], 'number'],
            [['finance_order_channel_name','finance_record_log_qiniuurl','finance_pay_channel_name'], 'string', 'max' => 100],
            [['finance_record_log_confirm_name'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('boss', '主键'),
            'finance_order_channel_id' => Yii::t('boss', '对账名称id'),
            'finance_order_channel_name' => Yii::t('boss', '对账名称'),
            'finance_pay_channel_id' => Yii::t('boss', '收款渠道id'),
            'finance_pay_channel_name' => Yii::t('boss', '收款渠道名称'),
            'finance_record_log_succeed_count' => Yii::t('boss', '成功记录数'),
            'finance_record_log_succeed_sum_money' => Yii::t('boss', '成功记录数总金额'),
            'finance_record_log_manual_count' => Yii::t('boss', '人工确认笔数'),
            'not_dispose_nub' => Yii::t('boss', '未处理笔数'),
            'not_dispose_sum' => Yii::t('boss', '未处理总额'),		
            'finance_record_log_manual_sum_money' => Yii::t('boss', '人工确认金额'),
            'finance_record_log_failure_count' => Yii::t('boss', '失败笔数'),
            'finance_record_log_failure_money' => Yii::t('boss', '失败总金额'),
            'finance_record_log_statime' => Yii::t('boss', '账期开始时间'),
            'finance_record_log_endtime' => Yii::t('boss', '账期结束时间'),
            'finance_record_log_confirm_name' => Yii::t('boss', '对账人'),
            'finance_record_log_fee' => Yii::t('boss', '服务费'),
            'finance_record_log_qiniuurl' => Yii::t('boss', '七牛地址'),
            'create_time' => Yii::t('boss', '创建时间'),
            'is_del' => Yii::t('boss', '0 正常 1 删除'),
        ];
    }
}
