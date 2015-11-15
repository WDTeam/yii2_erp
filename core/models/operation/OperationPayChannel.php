<?php

/**
* 控制器对接  支付渠道
* ==========================
* 北京一家洁 版权所有 2015-2018 
* ----------------------------
* 这不是一个自由软件，未经授权不许任何使用和传播。
* ==========================
* @date: 2015-11-10
* @author: peak pan 
* @version:1.0
*/
namespace core\models\operation;

use Yii;
use yii\helpers\ArrayHelper;

class OperationPayChannel extends \dbbase\models\operation\OperationPayChannel
{
   
	
	/**
	* 函数用途描述 返回支付渠道列表
	* @date: 2015-11-10
	* @author: peak pan
	* @return:
	**/
	
	public static function getpaychannellist($type){
		
		$data = self::find();
		if($type=='all'){	
		$info='all';	
		}else{
			$data =$data->where(['operation_pay_channel_type'=>$type]);
		}
		$data = $data->select('id,operation_pay_channel_name')->asArray()->all();
		
		
		return ArrayHelper::map($data,'id','operation_pay_channel_name');
		
	}
	
	
	
	/**
	 * 返回通过id返渠道的名称
	 * @date: 2015-11-10
	 * @author: peak pan
	 * @return:
	 **/
	
	public static function get_post_name($id){
		$data = self::find()->select('operation_pay_channel_name')->where(['id'=>$id])->asArray()->one();
		if(!empty($data)){
			return $data['operation_pay_channel_name'];
			
		}else{
			return '未知';
			
		}
	}
	
	/**
	 * 返回通过名称返id
	 * @date: 2015-11-10
	 * @author: peak pan
	 * @return:
	 **/
	
	public static function get_post_id($name){
		$data = self::find()->select('id')->where(['operation_pay_channel_name'=>$name])->asArray()->one();
		if(!empty($data)){
			return $data['id'];	
		}else{
			return 0;	
		}
	}
	
	
	
	/**
	 * 订单配置项
	 * @date: 2015-11-10
	 * @author: peak pan
	 * @return:
	 **/
	public static  function configpay($id)
	{
		$configdate=['1' => '在线支付', '2' => 'e家洁','3'=>'第三方'];
		$data = self::findOne($id);
		$array=[$data['operation_pay_channel_type'],$configdate[$data['operation_pay_channel_type']]];
		return $array;	
	}
	
	
	
	
}
