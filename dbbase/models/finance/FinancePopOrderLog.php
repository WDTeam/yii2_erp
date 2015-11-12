<?php
/**
* 对账日志数据库映射关系 
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
 * This is the model class for table "{{%finance_pop_order_log}}".
 *
 * @property integer $id
 * @property string $finance_pay_order_num
 * @property string $finance_pop_order_number
 * @property integer $finance_pop_order_log_series_succeed_status
 * @property integer $finance_pop_order_log_series_succeed_status_time
 * @property integer $finance_pop_order_log_finance_status
 * @property integer $finance_pop_order_log_finance_status_time
 * @property integer $finance_pop_order_log_finance_audit
 * @property integer $finance_pop_order_log_finance_audit_time
 * @property integer $create_time
 * @property integer $is_del
 */
class FinancePopOrderLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance_pop_order_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['finance_pop_order_log_series_succeed_status', 'finance_pop_order_log_series_succeed_status_time', 'finance_pop_order_log_finance_status', 'finance_pop_order_log_finance_status_time', 'finance_pop_order_log_finance_audit', 'finance_pop_order_log_finance_audit_time', 'create_time', 'is_del'], 'integer'],
            [['finance_pay_order_num', 'finance_pop_order_number'], 'string', 'max' => 40]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('boss', '主键'),
            'finance_pay_order_num' => Yii::t('boss', '官方系统订单号'),
            'finance_pop_order_number' => Yii::t('boss', '第三方订单号'),
            'finance_pop_order_log_series_succeed_status' => Yii::t('boss', '系统对账成功'),
            'finance_pop_order_log_series_succeed_status_time' => Yii::t('boss', '系统对账成功时间'),
            'finance_pop_order_log_finance_status' => Yii::t('boss', '财务确定 '),
            'finance_pop_order_log_finance_status_time' => Yii::t('boss', '财务 1 失败'),
            'finance_pop_order_log_finance_audit' => Yii::t('boss', '财务未处理'),
            'finance_pop_order_log_finance_audit_time' => Yii::t('boss', '财务未处理时间'),
            'create_time' => Yii::t('boss', '创建时间'),
            'is_del' => Yii::t('boss', '0 正常 1 删除'),
        ];
    }
}
