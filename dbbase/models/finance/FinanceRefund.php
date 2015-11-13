<?php
/**
* 退款数据库映射关系 
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
use core\models\system\SystemUser;
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
            [['finance_refund_stype', 'create_time'], 'required'],
            [['finance_refund_money', 'finance_refund_discount'], 'number'],
            [['finance_refund_stype','finance_refund_check_id', 'finance_refund_pay_create_time', 'finance_pay_channel_id', 'finance_refund_pay_status', 'finance_order_channel_id', 'finance_refund_worker_id', 'isstatus', 'create_time','finance_refund_county_id','finance_refund_city_id','finance_refund_province_id','finance_refund_shop_id','is_del'], 'integer'],
            [['finance_refund_pop_nub'], 'string', 'max' => 40],
            [['finance_refund_tel', 'finance_refund_worker_tel'], 'string', 'max' => 20],
            [['finance_refund_reason'], 'string', 'max' => 255],
            [['finance_pay_channel_title','finance_refund_check_name', 'finance_refund_pay_flow_num'], 'string', 'max' => 80],
            [['finance_order_channel_title'], 'string', 'max' => 30]
        ];
    }

    
    
    
   static  public function get_adminname($id)
    {
    
    	$admininfo=SystemUser::findIdentity($id);
    	if($admininfo){$adminname=$admininfo->username;}else{$adminname='未查到';}
    	
    	return $adminname;
    
    }
    
    static  public function get_status($id)
    {
    	switch ($id)
    	{
    		case 1:
    			return '取消 ';
    			break;
    		case 2:
    			return '退款的';
    			break;
    		case 3:
    			return '财务已审核';
    			break;
    		case 4:
    			return '财务已退款';
    			break;
    		case 5:
    			return '其他';
    			break;
    	}
    	
    }
    
    
    
    
    
    /**
     * @inheritdoc
     */
    
    public $refund_money;
    public $create_time_end;
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('core', '主键id'),
            'finance_refund_pop_nub' => Yii::t('core', '流水号'),
            'customer_id' => Yii::t('core', '用户ID'),
            'finance_refund_tel' => Yii::t('core', '用户电话'),
            'finance_refund_money' => Yii::t('core', '退款金额'),
            'finance_refund_stype' => Yii::t('core', '申请方式'),
            'finance_refund_reason' => Yii::t('core', '退款理由'),
            'finance_refund_discount' => Yii::t('core', '优惠价'),
            'finance_refund_pay_create_time' => Yii::t('core', '支付时间'),
            'finance_pay_channel_id' => Yii::t('core', '支付方式id'),
            'finance_pay_channel_title' => Yii::t('core', '支付方式'),
            'finance_refund_pay_status' => Yii::t('core', '支付状态 '),//1支付 0 未支付 2 其他
            'finance_refund_pay_flow_num' => Yii::t('core', '订单号'),
            'finance_order_channel_id' => Yii::t('core', '订单渠道id'),
            'finance_order_channel_title' => Yii::t('core', '订单渠道'),
            'finance_refund_worker_id' => Yii::t('core', '服务阿姨'),
            'finance_refund_worker_tel' => Yii::t('core', '阿姨电话'),
            'finance_refund_check_id' => Yii::t('core', '确认人id'),
            'finance_refund_check_name' => Yii::t('core', '确认人'),
            'finance_refund_shop_id' => Yii::t('core', '门店'),
            'finance_refund_province_id' => Yii::t('core', '省份'),
            'finance_refund_city_id' => Yii::t('core', '城市'),
            'finance_refund_county_id' => Yii::t('core', '区'),
            'isstatus' => Yii::t('core', '处理状态'),//是否取消1 取消 2 退款的 3 财务已经审核 4 财务已经退款
            'create_time' => Yii::t('core', '退款开始时间'),
            'create_time_end' => Yii::t('core', '退款结束时间'),
            'refund_money' => Yii::t('core', '统计行'),
            'is_del' => Yii::t('core', '0 正常 1删除'),
        ];
    }
}
