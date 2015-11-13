<?php
/**
* 对账数据库映射关系 
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
 * This is the model class for table "{{%finance_pop_order}}".
 *
 * @property integer $id
 * @property string $finance_pop_order_number
 * @property integer $finance_order_channel_id
 * @property string $finance_order_channel_title
 * @property integer $finance_pay_channel_id
 * @property string $finance_pay_channel_title
 * @property string $finance_pop_order_customer_tel
 * @property integer $finance_pop_order_worker_uid
 * @property integer $finance_pop_order_booked_time
 * @property integer $finance_pop_order_booked_counttime
 * @property string $finance_pop_order_sum_money
 * @property string $finance_pop_order_coupon_count
 * @property integer $finance_pop_order_coupon_id
 * @property string $finance_pop_order_order2
 * @property string $finance_pop_order_channel_order
 * @property integer $finance_pop_order_order_type
 * @property integer $finance_pop_order_status
 * @property integer $finance_pop_order_finance_isok
 * @property string $finance_pop_order_discount_pay
 * @property string $finance_pop_order_reality_pay
 * @property integer $finance_pop_order_order_time
 * @property integer $finance_pop_order_pay_time
 * @property integer $finance_pop_order_pay_status
 * @property string $finance_pop_order_pay_title
 * @property integer $finance_pop_order_check_id
 * @property integer $finance_pop_order_finance_time
 * @property integer $create_time
 * @property integer $is_del
 */
class FinancePopOrder extends \yii\db\ActiveRecord
{
	
	public $finance_uplod_url;
	
	public $finance_pop_order_info_msg;
	public $finance_pop_order_no;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance_pop_order}}';
    }

    
    
    public static function get_cache_tiem()
    {
    	if(\Yii::$app->cache->exists('lastidinfoid')){
    		$orderchannellist= \Yii::$app->cache->get('lastidinfoid');
    		return json_decode($orderchannellist,true);
    	}/* else{
    		\Yii::$app->cache->set('lastidinfoid',$key,5);
    		return $key;
    	} */
    }
    
    
    public static function get_pay_status($statusid)
    {
    	switch ($statusid)
    	{
    		case 1:
    			return '结算';
    			break;
    		case 2:
    			return '退款';
    			break;
    		case 3:
    			return '服务费';
    			break;
    		case 4:
    			return '转账';
    			break;
    		case 5:
    			return '交易失败';
    			break;
    	}
    }

    
    /**
    * 通过渠道的第三方的账期id
    * @date: 2015-10-23
    * @author: peak pan
    * @return:
    **/
    
    
   static  public function get_in_list_id($id){

   	$alinfo=FinancePopOrder::find()
   	->select('finance_pop_order_number')
   	->andWhere(['=','finance_record_log_id',$id])
   	->asArray()->All();
   	
   	foreach ($alinfo as $rty){
   		$rtystye[]=$rty['finance_pop_order_number'];
   	}
   	if(count($rtystye)>0){
   		return $rtystye;
   	}else{
   		return 1;
   	}
   	
   	
   }
    
     
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['finance_order_channel_id','order_code','order_status_name','order_money','finance_status','finance_order_channel_statuspayment','finance_order_channel_endpayment','finance_order_channel_title', 'finance_pay_channel_id', 'finance_pay_channel_title'], 'required'],
            [['finance_record_log_id','finance_order_channel_id', 'finance_pay_channel_id', 'finance_pop_order_worker_uid', 'finance_pop_order_booked_time', 'finance_pop_order_booked_counttime', 'finance_pop_order_coupon_id', 'finance_pop_order_order_type', 'finance_pop_order_status', 'finance_pop_order_finance_isok', 'finance_pop_order_order_time', 'finance_pop_order_pay_time', 'finance_pop_order_pay_status','finance_pop_order_pay_status_type', 'finance_pop_order_check_id', 'finance_pop_order_finance_time', 'create_time', 'is_del'], 'integer'],
            [['finance_pop_order_sum_money', 'finance_pop_order_coupon_count', 'finance_pop_order_discount_pay', 'finance_pop_order_reality_pay'], 'number'],
            [['finance_pop_order_number', 'finance_pop_order_order2', 'finance_pop_order_channel_order'], 'string', 'max' => 40],
            [['finance_order_channel_title', 'finance_pay_channel_title'], 'string', 'max' => 80],
            [['finance_pop_order_customer_tel'], 'string', 'max' => 20],
            [['finance_pop_order_msg'], 'string', 'max' => 200],
            [['finance_pop_order_pay_title','finance_uplod_url'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '主键id'),
            'order_code' => Yii::t('app', '系统订单号'),
            'order_status_name' => Yii::t('app', '系统状态'),
            'order_money' => Yii::t('app', '系统金额'),
            'finance_status' => Yii::t('app', '收款状态'),
            'finance_pop_order_no' => Yii::t('app', '标记坏账'),
            'finance_uplod_url' => Yii::t('app', '上传对账单'),
            'finance_record_log_id' => Yii::t('app', '账期id'),
            'finance_pop_order_number' => Yii::t('app', '第三方订单号'),
            'finance_order_channel_id' => Yii::t('app', '下单渠道'),
            'finance_order_channel_title' => Yii::t('app', '下单渠道名称'),
            'finance_pay_channel_id' => Yii::t('app', '支付渠道'),
            'finance_pay_channel_title' => Yii::t('app', '支付渠道名称'),
            'finance_pop_order_customer_tel' => Yii::t('app', '用户电话'),
            'finance_pop_order_worker_uid' => Yii::t('app', '服务阿姨'),
            'finance_pop_order_booked_time' => Yii::t('app', '预约开始时间'),
            'finance_pop_order_booked_counttime' => Yii::t('app', '预约服务时长'),
            'finance_pop_order_sum_money' => Yii::t('app', '总金额'),
            'finance_pop_order_coupon_count' => Yii::t('app', '优惠卷金额'),
            'finance_pop_order_coupon_id' => Yii::t('app', '优惠卷id'),
            'finance_pop_order_order2' => Yii::t('app', '子订单号'),
            'finance_pop_order_channel_order' => Yii::t('app', '获取渠道唯一订单号'),
            'finance_pop_order_order_type' => Yii::t('app', '订单类型'),
            'finance_pop_order_status' => Yii::t('app', '支付状态'),
            'finance_pop_order_finance_isok' => Yii::t('app', '1 对账成功 2 财务确定ok  3 财务确定on 4 财务未处理'),
            'finance_pop_order_discount_pay' => Yii::t('app', '优惠金额'),
            'finance_pop_order_reality_pay' => Yii::t('app', '实际收款'),
            'finance_pop_order_order_time' => Yii::t('app', '下单时间'),
            'finance_pop_order_pay_time' => Yii::t('app', '支付时间'),
            'finance_pop_order_pay_status' => Yii::t('app', '处理状态'),
            'finance_pop_order_pay_title' => Yii::t('app', '状态 描述'),
            'finance_pop_order_check_id' => Yii::t('app', '操作人id'),
            'finance_pop_order_finance_time' => Yii::t('app', '对账时间'),
            'finance_pop_order_pay_status_type' => Yii::t('app', '对账状态'),
            'finance_order_channel_statuspayment' => Yii::t('app', '开始账期'),
            'finance_order_channel_endpayment' => Yii::t('app', '结束账期'),
            'finance_pop_order_info_msg' => Yii::t('app', '订单状态机'),
            'finance_pop_order_msg' => Yii::t('app', '原因'),
            'create_time' => Yii::t('app', '增加时间'),
            'is_del' => Yii::t('app', '0 正常 1 删除'),
        ];
    }
}
