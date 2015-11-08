<?php
/**
* API 使用控制器
* ==========================
* 北京一家洁 版权所有 2015-2018 
* ----------------------------
* 这不是一个自由软件，未经授权不许任何使用和传播。
* ==========================
* @date: 2015-11-7
* @author: peak pan 
* @version:1.0
*/

namespace core\models\operation\coupon;

use Yii;
use yii\base\InvalidParamException;
use core\models\operation\coupon\CouponCode;
use core\models\operation\coupon\CouponCustomer;
use core\models\customer\Customer;
use core\models\operation\coupon\CouponRule;

class CouponUserinfo extends \dbbase\models\operation\coupon\CouponUserinfo
{

///////////////////////////////////////未知/////////////////////////////////////////
	
	
	public function behaviors()
	{
		return [
		[
		'class' => TimestampBehavior::className(),
		'createdAtAttribute' => 'created_at',
		'updatedAtAttribute' => 'updated_at',
		],
		];
	}
	
	/**
	 * relation coupon code
	 */
	public function getCouponCode(){
		return $this->hasMany(CouponCode::className(), ['id'=>'coupon_code_id'])->onCondition([]);
	}
	/**
	 * relation coupon
	 */
	public function getCoupon(){
		return $this->hasMany(Coupon::className(), ['id'=>'coupon_id'])->onCondition([]);
	}
	
	/**
	 * list customer's coupon while phone is access
	 */
	public static function listCustomerCoupon($phone){
		//check customer is exist
		$customer =  Customer::find()->where(['customer_phone'=>$phone])->one();
		if($customer == NULL) return false;
	
		//list coupon
		$couponCustomer = self::find()->where(['customer_id'=>$customer->id])
		->joinWith('coupon')
		->joinWith('couponCode')
		->orderBy('expirate_at asc, coupon_price desc')
		->all();
	
		 
		return $couponCustomer;
	}
	
	
////////////////////////////////////////////////////////////////////////////////
	

	/**
	* 兑换优惠券
	* @date: 2015-11-7
	* @author: peak pan
	* @return:
	**/
	
	public static function generateCouponByCode($phone, $code){
		//check customer exists
		 
		$customer = Customer::find()->where(['customer_phone'=>$phone])->one();
		if($customer == NULL){
			//手机号不存在，无此用户  是否创建此用户
			$array=[
			'is_status'=>1,
			'msg'=>'此手机号未被注册！',
			'date'=>''
			];
			return $array;
		}
		
		//检查优惠码是否已经被兑换
		$couponCustomer=self::find()->where(['coupon_userinfo_code'=>$code])->one();
		if(!empty($couponCustomer)){
			$array=[
			'is_status'=>1,
			'msg'=>'优惠码已经被领取或使用',
			'date'=>'',
			];
			return $array;
		}
		

		$coupon=substr($code,0,3);
		//查看渠道下面的领取开始时间是不是可以领取
		$Couponruledate = CouponRule::find()->where(['couponrule_Prefix'=>$coupon])->asArray()->one();
		if($Couponruledate['id']<>''){
			$array=[
			'is_status'=>1,
			'msg'=>'优惠券不存在',
			'date'=>'',
			];
			return $array;
			
		}
		
		 $code_able = self::checkCouponIsclok($code);
		 if($code_able){
		 	$array=[
		 	'is_status'=>1,
		 	'msg'=>'优惠券不可用',
		 	'date'=>'',
		 	];
		 	return $array;
		 }
		 
		if($Couponruledate->couponrule_get_end_time < time()) {
			$array=[
			'is_status'=>1,
			'msg'=>'优惠券兑换时间已过期',
			'date'=>'',
			];
			return $array;
			
		}
		
		if($Couponruledate->is_del==1) {
			$array=[
			'is_status'=>1,
			'msg'=>'优惠券已删除',
			'date'=>'',
			];
			return $array;
				
		}
		
		
		if($coupon->is_disabled==2){
			$array=[
			'is_status'=>1,
			'msg'=>'优惠券兑换已禁用',
			'date'=>'',
			];
			return $array;	
		}

		
		//$customer//客户信息  $Couponruledate  优惠券规则数据
		
		//erate coupon for customer
		$couponCustomer = new CouponUserinfo;
		$couponCustomer->customer_id = $customer->id;
		$couponCustomer->customer_tel = $customer->customer_phone;
		$couponCustomer->coupon_userinfo_id = $Couponruledate['id'];
		$couponCustomer->coupon_userinfo_code =$code;
		$couponCustomer->coupon_userinfo_name = $Couponruledate['couponrule_name'];//优惠券名称
		$couponCustomer->coupon_userinfo_price =$Couponruledate['couponrule_price'];//优惠券价值
		$couponCustomer->coupon_userinfo_gettime = time();
		$couponCustomer->coupon_userinfo_usetime = 0;//使用
		$couponCustomer->coupon_userinfo_endtime =$Couponruledate['coupon_userinfo_endtime'];//
		$couponCustomer->order_code ='0';
		$couponCustomer->system_user_id = $customer->id;
		$couponCustomer->system_user_name = '用户自对';
		$couponCustomer->is_used = 0;
		$couponCustomer->created_at = time();//创建时间
		$couponCustomer->updated_at =time();
		$couponCustomer->is_del = 0;//状态
		//成功之后删除优惠码（从redis）
		
		$couponCustomer->validate();
		if($couponCustomer->hasErrors()){
			return false;
		}
		$couponCustomer->save();
		
		if($couponCustomer){
			$rt=\Yii::$app->redis->SREM($coupon,$code);
			$array=[
			'is_status'=>1,
			'msg'=>'数据库写入成功',
			'date'=>$couponCustomer,
			];
			
			
			return $array;
		}else{
			
			$array=[
			'is_status'=>1,
			'msg'=>'数据库写入失败',
			'date'=>$couponCustomer,
			];
			
			return $array;
		}
		
		 
	}
	
	/**
	* 获取用户优惠券列表（列表包括下单所在城市的和所有城市都通用的券）  
	* @date: 2015-11-7   api
	* @author: peak pan
	* @param $customer_id int 用户id
	* @param $city_id int 城市id
	* @return $couponCustomer 用户优惠券列表
	**/
	
	public static function GetCustomerCouponList($customer_id,$city_id,$service_type_id){
		$now_time=time();
		$couponCustomer=(new \yii\db\Query())->select('*')->from('{{%coupon}}')
		->leftJoin('{{%coupon_customer}}', '{{%coupon_customer}}.coupon_id = {{%coupon}}.id')
		->where(['and',"{{%coupon}}.coupon_end_at>$now_time",'{{%coupon_customer}}.is_del=0','{{%coupon_customer}}.is_used=0',"{{%coupon_customer}}.customer_id=$customer_id", ['or', ['and','{{%coupon}}.coupon_city_limit=1',"{{%coupon}}.coupon_city_id=$city_id"], '{{%coupon}}.coupon_city_limit=0'],['or', ['and','{{%coupon}}.coupon_type!=0',"{{%coupon}}.coupon_service_type_id=$service_type_id"], '{{%coupon}}.coupon_type=0']] )
		->orderBy(['{{%coupon}}.coupon_end_at'=>SORT_ASC,'{{%coupon_customer}}.coupon_price'=>SORT_DESC])->all();
		return $couponCustomer;
	}
	

	
	
	
	
	
	/**
	* 获取用户优惠券列表（列表包括下单所在城市的和所有城市都通用的券和过期三十天内的）
	* @date: 2015-11-7   当前城市下可用（包含通用）排列按照过期时间正序  价格倒序               过期的  30内的包含30 按照价格倒序
	* @author: peak pan
	* @param $customer_id int 用户id
	* @param $city_id int 城市id
	* @return $couponCustomer 用户优惠券列表
	**/
	
	
	
	
	public static function GetCustomerDueCouponList($customer_id,$city_id){
		$now_time= date("Y-m-d",time());
		$last_month = strtotime("$now_time -30 days");
		$couponCustomer=(new \yii\db\Query())->select('*')->from('{{%coupon}}')
		->leftJoin('{{%coupon_customer}}', '{{%coupon_customer}}.coupon_id = {{%coupon}}.id')
		->where(['and',"{{%coupon}}.coupon_end_at>$last_month",'{{%coupon_customer}}.is_del=0','{{%coupon_customer}}.is_used=0',"{{%coupon_customer}}.customer_id=$customer_id", ['or', ['and','{{%coupon}}.coupon_city_limit=1',"{{%coupon}}.coupon_city_id=$city_id"], '{{%coupon}}.coupon_city_limit=0']] )
		->orderBy(['{{%coupon}}.coupon_end_at'=>SORT_ASC,'{{%coupon_customer}}.coupon_price'=>SORT_DESC])->all();
		
		
		
		
		return $couponCustomer;
	}
	

	
	
	
	
	
	
	
	
	/**
	* 获取用户全部优惠券列表（包括可用的、不可用的、所有城市的、通用的）
	* @date: 2015-11-7  所有城市下可用（包含通用）排列按照过期时间正序       不可用按照价格倒序
	* @author: peak pan
	* @param $customer_id int 用户id
	* @return $couponCustomer 用户优惠券列表
	**/
	
	
	public static function GetAllCustomerCouponList($customer_id){
		
		/* $couponCustomer=(new \yii\db\Query())->select('*')->from('{{%coupon}}')
		->leftJoin('{{%coupon_customer}}', '{{%coupon_customer}}.coupon_id = {{%coupon}}.id')
		->where(['and','{{%coupon_customer}}.is_del=0',"{{%coupon_customer}}.customer_id=$customer_id"] )
		->orderBy(['{{%coupon}}.coupon_end_at'=>SORT_ASC,'{{%coupon_customer}}.coupon_price'=>SORT_DESC])->all(); */
		
		$couponCustomer = (new \yii\db\Query())
		->select('*')
		->from(['cc'=>'{{%coupon_userinfo}}'])
		->leftJoin(['c'=>'{{%coupon_rule}}'], 'c.id = cc.coupon_userinfo_id')
		->where(['cc.customer_id'=>$customer_id])
		->andWhere(['cc.is_del'=>0])
		->orderBy(['{{%coupon_rule}}.couponrule_use_end_time'=>SORT_ASC,'{{%coupon_userinfo}}.coupon_userinfo_price'=>SORT_DESC])
		->all();
		
		return $couponCustomer;
	}
	 
	
	
	
	
	

	/**
	* 获取用户优惠码数量
	* @date: 2015-11-7
	* @author: peak pan
	* @customer_id int    用户id
	* @return  array 用户优惠码
	**/
	
	public static function CouponCount($customer_id)
	{
		$now_time=time();
		return CouponCustomer::find()->where(['and','is_del=0',"customer_id=$customer_id",'is_used=0',"coupon_userinfo_endtime>$now_time"] )->count();
		
		
	}
	

	
	
	
	
	
	/**
	* 验证优惠码是否绑定用户
	* @date: 2015-11-7
	* @author: peak pan
	* @param $code string 优惠码
	* @return ture已绑定 false未绑定
	**/

 private  function checkCouponIsUsed($code,$customer_id)
	{
		$couponCustomer=self::find()->where(['coupon_userinfo_code'=>$code,'customer_id'=>$customer_id])->one();
		if(empty($couponCustomer)){
			return false;
		}else{
			return true;
		}
	}
	
	/**
	* 优惠码是否可用
	* @date: 2015-11-7
	* @author: peak pan
	* @return:
	**/
	private  function checkCouponIsclok($code)
	{
		$now_time=time();
		$couponCustomer=self::find()->where(['and','coupon_userinfo_code'=>$code,'is_used'=>1,"coupon_userinfo_endtime > $now_time"])->one();
		if($couponCustomer){
			//查询存在  
			return true;
		}else{
			return false;
		}
	}
	
	
	
	
	
	
	
}
