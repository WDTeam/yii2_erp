<?php
namespace boss\controllers;

use boss\components\Controller;
use yii\db\Query;
use common\models\CustomerCode;
use common\models\CustomerAccessToken;

//错误返回码定义
// 80001: 电话号码为空
// 80002：电话号码不匹配
// 80003：验证码为空
// 80004：验证码不正确
// 80005：
/**
 * 顾客端API
 * @author:liudaoqiang@corp.1jiajie.com
 */
class CustomerCenterController extends Controller
{
	public function actionIndex()
	{
		echo "index";
	}

	/**
	 * 生成并发送验证码子程序
	 */
	public function actionSendCode()
	{
		$request = \YII::$app->request;
		$phone = $request->get("phone");

		//验证手机号码
		if (empty($phone)) {
			$return = ["errcode"=>"80001", "errmsg"=>"手机号码为空"];
			return json_encode($return);
		}

		if (!preg_match("/^[1-9]\d{10}$/i", $phone)) {
			$return = ["errcode"=>"80002", "errmsg"=>"手机号码不匹配"];
		}

		//生成验证码
		$code = "";
		for ($i=0; $i < 4; $i++) { 
			$code .= rand(0, 9);
		}

		//将验证码存入数据库
		$customerCode = new CustomerCode;
		$customerCode->customer_code = $code;
		$customerCode->customer_phone = $phone;
		$customerCode->customer_code_expiration = 60;
		$customerCode->created_at = time();
		$customerCode->updated_at = 0;
		$customerCode->is_del = 0;
		$customerCode->validate();
		if ($customerCode->hasErrors()) {
			$return = ['errcode'=>'', 'errmsg'=>'验证码持久化失败'];
			return json_encode($return);
		}
		$customerCode->save();

		//接口返回
		$return = ['errcode'=>0, 'errmsg'=>'ok', 'code'=>$code];
		echo json_encode($return);

		//将验证码存入数据缓存
		// $cache = Yii::$app->cache();
		// $dependency = new \yii\caching\FileDependency(['fileName' => 'code.txt']);
		// if (!$cache->get("code")) {
		// 	$cache->add("code", $code, 60, $dependency);
		// }else{
		// 	$cache->set("code", $code, 60, $dependency);
		// }

		//短息模板
		$content = "您此次的验证码为" . $code . ", 欢迎使用e家洁软件";
		//接收手机号码
		//短信内容
		$content = urlencode($content);
		$url = "http://utf8.sms.webchinese.cn/?Uid=liuzhiqiangxyz&Key=671aa7e94427d9c6eb6a&smsMob=".$phone."&smsText=".$content;
		if(function_exists('file_get_contents')){
		  	$file_contents = file_get_contents($url);
		}else{
			$ch = curl_init();
			$timeout = 5;
			curl_setopt ($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$file_contents = curl_exec($ch);
			curl_close($ch);
		}
		if (!$file_contents) {
			$return = ["errcode"=>80004, "errmsg"=>"发送短息验证码失败"];
			return json_encode($return);
		}
		$return = ["errcode"=>0, "errmsg"=>"ok", 'code'=>$code];
	}

	/**
	 * 生成并获取access_token子程序
	 * @api {get} /customer/:phone Request access_token 
	 * @api {get} /customer/:code Request access_token
	 * @apiName getAccessToken
	 * @apiGroup Customer
	 * 
	 * @apiSuccess {String} access_token
	 *
	 * @apiSuccessExample Success-Response
	 *	{
	 *		"errcode":0,
	 *		"errmsg":"success",
	 *		"access_token":"23234634367998989"
	 *	}
	 * @apiError get access_token error
	 * @apiErrorExample Error-Response
	 *	{
	 *		"errcode":"80001"
	 *		"errmsg":"获取access_token失败"
	 *	}
	 *
	 */
	public function actionGetAccessToken()
	{
		$return = [];

		$request = \YII::$app->request;
		$phone = trim($request->get("phone"));
		$code = trim($request->get("code"));	

		//验证手机号码
		if (empty($phone)) {
			$return = ["errcode"=>"80001", "errmsg"=>"手机号码为空"];
			return json_encode($return);
		}

		if (!preg_match("/^[1-9]\d{10}$/i", $phone)) {
			$return = ["errcode"=>"80002", "errmsg"=>"手机号码不匹配"];
			return json_encode($return);
		}

		//验证输入的验证码
		if (empty($code)) {
			$return = ["errcode"=>"80002", "errmsg"=>"验证码为空"];
			return json_encode($return);
		}

		$customerCode = CustomerCode::find()->where([
			'customer_code'=>$code,
			])->one();

		if ($customerCode == null) {
			$teturn = ['code'=>'80009', 'errmsg'=>'验证码错误或者已经过期'];
		}

		//获取当前时间
		$current_time_millis = time();

		//获取系统的key
		// $config = require(__DIR__ . "../config/main.php");
		// $sys_key = $config["sys_key"];
		$sys_key = 'adjaskldjskdfjsd';

		//生成access_token
		$access_token = md5($current_time_millis . $sys_key);

		//将access_token存入数据库
		$customerAccessToken = new CustomerAccessToken;
		$customerAccessToken->customer_access_token = $access_token;
		$customerAccessToken->customer_access_token_expiration = 3600 * 2;
		$customerAccessToken->customer_code_id = $customerCode->id;
		$customerAccessToken->customer_code = $customerCode->customer_code;
		$customerAccessToken->created_at = time();
		$customerAccessToken->updated_at = 0;
		$customerAccessToken->is_del = 0;
		$customerAccessToken->validate();
		if ($customerAccessToken->hasErrors()) {
			$return = ['errcode'=>80006, 'errmsg'=>'access_token持久化失败'];
			return json_encode($return);
		}
		$customerAccessToken->save();

		//返回access_token
		$return = ['errcode'=>0, 'errmsg'=>'ok', 'access_token'=>$customerAccessToken->customer_access_token];
		return json_encode($return);

		//base64加密算法
		// $access_token = base64_encode($access_token);
		// $return = ["errcode"=>0, "errmsg"=>"success", "access_token"=>$access_token];
		// return json_encode($return);


	}


	

	/**
	 * 服务列表获取接口
	 * @api {get} /customer/:access_token Request access_token 
	 * @apiName getServiceList
	 * @apiGroup Customer
	 * 
	 * @apiSuccess {String} access_token
	 *
	 * @apiSuccessExample Success-Response
	 *	{
	 *		"errcode":0,
	 *		"errmsg":"success",
	 *		"serviceList":
	 *	}
	 * @apiError get access_token error
	 * @apiErrorExample Error-Response
	 *	{
	 *		"errcode":"80001"
	 *		"errmsg":"获取服务列表失败"
	 *	}
	 *
	 */
	public function actionGetServiceList()
	{
		$return = [];

		$request = Yii::$app->request;
		$access_token = $request->get("access_token");

		//验证access_token是否为空
		if (empty(trim($access_token))) {
			$return = ["errcode"=>80001, "errmsg"=>'', "response"=>""];
			return json_encode($return);
		}

		//将access_token解密得到customer_id 和 生成access_token时的时间
		$access_token = base64_decode($access_token);
		$created_at = substr($access_token, 0, 11);

		//获取系统的key
		$config = require(__DIR__ . "../config/main.php");
		$sys_key = $config["sys_key"];
		$sys_key_length = count($sys_key);

		//获取系统设定的过期时间
		$expiration = $config["expiration"];

		//获取customer_id
		$customer_id_length = count($access_token) - 11 - $sys_key_length;
		$customer_id = substr($access_token, 11, $customer_id_length);

		//access_token是否已经过期
		if (time() - $created_at > $expiration) {
			$return = ["errcode"=>80007, "errmsg"=>'', "response"=>""];
			return json_encode($return);
		}

		//获取服务列表
		//调用core的服务列表获取接口，该接口暂无
	}

	public function actionGetOrderList(){
		\YII::$app->response->format = Response::FORMAT_JSON;
		return Order::findAll(['customer_id'=>$customer_id]);
	}


	/**
	 * 客户账户余额获取接口
	 */
	public function actionGetBalance()
	{
		$customer_id = \Yii::$app->request->get('customer_id');
		\Yii::$app->response->format = Response::FORMAT_JSON;

		$customer = Customer::find()->where([
			'id'=>$customer_id
			])->one();
		return ['errcode'=>0, 'errmsg'=>'ok', 'balance'=>$customer['customer_balance']];
	}

	/**
	 * 客户账户余额转入接口
	 */
	public function actionIncBalance()
	{
		$customer_id = \Yii::$app->request->get('customer_id');
		$cash = \Yii::$app->request->get('cash');
		\Yii::$app->response->format = Response::FORMAT_JSON;

		$customer = Customer::find()->where(['id'=>$customer_id])->one();
		$balance = $customer->balance;
		$customer->customer_balance += $cash;
		$customer->validate();
		$customer->save();
		return ['errcode'=>0, 'errmsg'=>'ok'];
	}

	/**
	 * 客户账户余额转出接口
	 */
	public function actionDecBalance()
	{
		$customer_id = \Yii::$app->request->get('customer_id');
		$cash = \Yii::$app->request->get('cash');
		\Yii::$app->response->format = Response::FORMAT_JSON;

		$customer = Customer::find()->where(['id'=>$customer_id])->one();
		$customer->customer_balance -= $cash;
		$customer->validate();
		$customer->save();
		return ['errcode'=>0, 'errmsg'=>'ok'];
	}
	
	/**
	 * 顾客端使用帮组查询接口
	 */
	public function actioGetHelpList()
	{
		\YII::$app->response->format = Response::FORMAT_JSON;
		return CustomerHelp::findAll();
	}

	/**
	 * 顾客端广告轮播图获取接口
	 */
	public function actionCarousel()
	{
		
	}
}
?>