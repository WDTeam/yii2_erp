<?php

namespace dbbase\models\finance;

use Yii;
use dbbase\models\finance\FinancePayChannel;

/**
 * This is the model class for table "{{%finance_order_channel}}".
 *
 * @property integer $id
 * @property string $finance_order_channel_name
 * @property integer $finance_order_channel_sort
 * @property integer $finance_order_channel_is_lock
 * @property integer $create_time
 * @property integer $is_del
 */
class FinanceOrderChannel extends \yii\db\ActiveRecord {
	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return '{{%finance_order_channel}}';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [ 
				[ 
						[ 
								'finance_order_channel_sort',
								'finance_order_channel_is_lock',
								'create_time',
								'is_del' 
						],
						'integer' 
				],
				[ 
						[ 
								'finance_order_channel_rate' 
						],
						'required' 
				],
				[ 
						[ 
								'finance_order_channel_name' 
						],
						'match',
						'pattern' => '/^[\w|\x{4e00}-\x{9fa5}]+$/u',
						'message' => '名字里面不能包含特殊符号' 
				],
				[ 
						[ 
								'finance_order_channel_name' 
						],
						'required' 
				],
				[ 
						[ 
								'finance_order_channel_rate',
								'finance_order_channel_name' 
						],
						'string',
						'max' => 50 
				] 
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [ 
				'id' => Yii::t ( 'boss', '主键id' ),
				'finance_order_channel_name' => Yii::t ( 'boss', '渠道名称' ),
				'finance_order_channel_rate' => Yii::t ( 'boss', '比例' ),
				'finance_order_channel_sort' => Yii::t ( 'boss', '支付显示' ),
				'finance_order_channel_is_lock' => Yii::t ( 'boss', '下单显示' ),
				'finance_order_channel_source' => Yii::t ( 'boss', '来源' ),
				'create_time' => Yii::t ( 'boss', '增加时间' ),
				'is_del' => Yii::t ( 'boss', '状态' ) 
		];
	}
	
	/**
	 * 获取支付渠道name
	 * @date: 2015-10-22
	 * 
	 * @author : peak pan
	 * @return :
	 *
	 */
	public static function get_pay_name($id) {
		$ordewhere ['id'] = $id;
		$payatainfo = FinancePayChannel::find ()->select ( 'finance_pay_channel_name' )->where ( $ordewhere )->asArray ()->one ();
		return $payatainfo ['finance_pay_channel_name'];
	}
	
	/**
	 * 获取订单渠道列表 支付使用
	 * @date: 2015-10-9
	 * 
	 * @author : peak pan
	 * @return :
	 *
	 */
	public static function get_order_channel_list() {
		// if(\Yii::$app->cache->exists('orderchannellist')){
		// $orderchannellist= \Yii::$app->cache->get('orderchannellist');
		// /return json_decode($orderchannellist,true);
		// }else{
		$ordewhere ['is_del'] = 0;
		$ordewhere ['finance_order_channel_sort'] = 1;
		$payatainfo = FinanceOrderChannel::find ()->select ( 'id,finance_order_channel_name' )->where ( $ordewhere )->asArray ()->all ();
		// \Yii::$app->cache->set('orderchannellist', json_encode($payatainfo));
		return $payatainfo;
		// }
	}
	
	/**
	 * 获取订单渠道列表 下单使用
	 * @date: 2015-10-9
	 * 
	 * @author : peak pan
	 * @return :
	 *
	 */
	public static function get_order_channel_list_info() {
		/*
		 * if(\Yii::$app->cache->exists('orderchannellistinfo')){ $orderchannellist= \Yii::$app->cache->get('orderchannellist'); return json_decode($orderchannellist,true); }else{
		 */
		$ordewhere ['is_del'] = 0;
		$ordewhere ['finance_order_channel_is_lock'] = 1; // 2 第三方不显示 1 第三方显示
		$payatainfo = FinanceOrderChannel::find ()->select ( 'id,finance_order_channel_name' )->where ( $ordewhere )->asArray ()->all ();
		// \Yii::$app->cache->set('orderchannellistinfo', json_encode($payatainfo));
		return $payatainfo;
		// }
	}
	
	/**
	 * 获取订单渠道名称
	 * 
	 * @param
	 *        	$id
	 * @return mixed
	 */
	public static function getOrderChannelByName($id) {
		
		// 定义缓存名称
		$cacheName = 'orderChannel_' . $id;
		
		// 判断是否存在缓存
		// if( $cache = Yii::$app->cache->get($cacheName) ){
		// return $cache['finance_order_channel_name'];
		// }
		
		// 生成缓存数据
		$data = self::find ()->where ( [ 
				'id' => $id 
		] )->asArray ()->one ();
		// self::createCache($cacheName,$data);
		
		// 返回渠道名称
		return $data ['finance_order_channel_name'] ? $data ['finance_order_channel_name'] : '官方支付对账';
	}
	public static function getOrderChannelByid($name) {
		$data = self::find ()->where ( [ 
				'finance_order_channel_name' => $name 
		] )->asArray ()->one ();
		return $data ['id'] ? $data ['id'] : 0;
	}
	
	/**
	 * 创建缓存
	 * 
	 * @param $name 缓存名称        	
	 * @param $data 缓存数据        	
	 */
	/*
	 * private static function createCache($name,$data) { \Yii::$app->cache->set($name, $data); }
	 */
	
	/**
	 * 根据渠道id获取渠道名称判断
	 * @date: 2015-10-9
	 * 
	 * @author : peak pan
	 * @return :
	 *
	 */
	public static function get_order_channel_info($channel_id = 0) {
		$channel_info = FinanceOrderChannel::findOne ( $channel_id );
		
		return $channel_info != NULL ? $channel_info : '未知';
	}
	public static function get_order_channel_listes() {
		$ordewhere ['is_del'] = 0;
		$payatainfo = FinanceOrderChannel::find ()->where ( $ordewhere )->asArray ()->all ();
		foreach ( $payatainfo as $errt ) {
			$tyd [] = $errt ['id'];
			$tydtui [] = $errt ['finance_order_channel_name'];
		}
		$tyu = array_combine ( $tyd, $tydtui );
		return $tyu;
	}
}
