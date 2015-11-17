<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151105_101733_create_table_coupon_rule extends Migration
{

    public function up()
    {
		$tableOptions = null;

		if($this->db->driverName === 'mysql'){
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'优惠规则表\'';
		}
		$this->createTable('{{%coupon_rule}}',[
			'id'				            => Schema::TYPE_BIGPK .'(20) NOT NULL AUTO_INCREMENT COMMENT \'id\'',
			'couponrule_name' 			    => Schema::TYPE_STRING.'(100) NOT NULL DEFAULT 0 COMMENT \'优惠券名称\'',
			'couponrule_channelname' 		=> Schema::TYPE_STRING.'(80) DEFAULT \'\' COMMENT \'渠道名称(主要使用到一码多用分渠道发)\'',
			'couponrule_classify' 			=> Schema::TYPE_INTEGER.'(4) NOT NULL DEFAULT 0 COMMENT \'1 一码一用  2 一码多用\'',
			'couponrule_category' 			=> Schema::TYPE_INTEGER.'(4) DEFAULT 0 COMMENT \'优惠券分类0为一般优惠券1为赔付优惠券\'',
			'couponrule_category_name' 		=> Schema::TYPE_STRING.'(100) DEFAULT \'0\' COMMENT \'优惠券范畴\'',
			'couponrule_type' 		        => Schema::TYPE_STRING.'(40) DEFAULT 0 COMMENT \'实收金额优惠券类型0为全网优惠券1为类别优惠券2为商品优惠券\'',
			'couponrule_type_name' 		    => Schema::TYPE_STRING.'(100)  DEFAULT \'0\' COMMENT \'优惠券类型名称\'',
			'couponrule_service_type_id' 	=> Schema::TYPE_INTEGER.'(4) DEFAULT 0 COMMENT \'服务类别id\'',
			'couponrule_service_type_name' 	=> Schema::TYPE_STRING.'(100)  DEFAULT \'\' COMMENT \'服务类别名称\'',
			'couponrule_commodity_id' 		=> Schema::TYPE_INTEGER.'(4) DEFAULT 0 COMMENT \'如果是商品优惠券id\'',
			'couponrule_commodity_name' 	=> Schema::TYPE_STRING.'(11) DEFAULT \'\' COMMENT \'如果是商品名称\'',
			'couponrule_city_limit' 		=> Schema::TYPE_INTEGER.'(4) DEFAULT 0 COMMENT \'城市限制0为不限1为单一城市限制\'',
			'couponrule_city_id' 			=> Schema::TYPE_INTEGER.'(8) DEFAULT 0 COMMENT \'关联城市\'',
			'couponrule_city_name' 			=> Schema::TYPE_STRING.'(32) DEFAULT 0 COMMENT \'城市名称\'',
			'couponrule_customer_type' 		=> Schema::TYPE_INTEGER.'(2) DEFAULT 0 COMMENT \'适用客户类别逗号分割0为所有用户1为新用户2为老用户3会员4为非会员\'',
			'couponrule_customer_type_name' => Schema::TYPE_STRING.'(100) DEFAULT \'\' COMMENT \'适用客户类别名称\'',

			'couponrule_get_start_time' 	=> Schema::TYPE_INTEGER.'(11) DEFAULT 0 COMMENT \'优惠券的用户可领取开始时间\'',
			'couponrule_get_end_time' 		=> Schema::TYPE_INTEGER.'(11) DEFAULT 0 COMMENT \'优惠券的用户可领取结束时间\'',
			'couponrule_use_start_time' 	=> Schema::TYPE_INTEGER.'(11) DEFAULT 0 COMMENT \'优惠券的用户可使用的开始时间\'',
			'couponrule_use_end_time' 		=> Schema::TYPE_INTEGER.'(11) DEFAULT 0 COMMENT \'优惠券的用户可使用的结束时间\'',

			'couponrule_code' 				=> Schema::TYPE_STRING.'(10) DEFAULT 0 COMMENT \'如果是1码多用的优惠码\'',
			'couponrule_Prefix' 			=> Schema::TYPE_STRING.'(20)  DEFAULT \'\' COMMENT \'优惠码前缀\'',
			'couponrule_use_end_days' 		=> Schema::TYPE_SMALLINT.'(2) DEFAULT 1 COMMENT \'领取后过期天数\'',
			'couponrule_promote_type' 		=> Schema::TYPE_INTEGER.'(11) DEFAULT 0 COMMENT \'优惠券优惠类型0为立减1为满减2为每减\'',
			'couponrule_promote_type_name' 	=> Schema::TYPE_STRING.'(30)  DEFAULT \'\' COMMENT \'优惠券优惠类型名\'',
			'couponrule_order_min_price'  	=> Schema::TYPE_DECIMAL . '(8,2) DEFAULT 0 COMMENT \'最小金额\'',
			'couponrule_price' 				=> Schema::TYPE_DECIMAL.'(8,2) DEFAULT 0 COMMENT \'满减或每减时订单最小金额\'',
			'couponrule_price_sum' 			=> Schema::TYPE_DECIMAL.'(8,2) DEFAULT 0 COMMENT \'优惠码总额\'',
			'couponrule_code_num'			 => Schema::TYPE_INTEGER.'(8) DEFAULT 0 COMMENT \'优惠码个数\'',
			'couponrule_code_max_customer_num' 		=> Schema::TYPE_INTEGER.'(11) DEFAULT 0 COMMENT \'创建时间\'',
			'is_disabled' 					=> Schema::TYPE_INTEGER.'(1) DEFAULT 0 COMMENT \'是否禁用\'',
			'system_user_id' 				=> Schema::TYPE_INTEGER.'(11) DEFAULT 0 COMMENT \'优惠码创建人id\'',
			'system_user_name' 				=> Schema::TYPE_STRING.'(40) DEFAULT \'\' COMMENT \'优惠码创建人\'',
			'created_at' 					=> Schema::TYPE_INTEGER.'(11) DEFAULT 0 COMMENT \'创建时间\'',
			'updated_at' 					=> Schema::TYPE_INTEGER.'(11) DEFAULT 0 COMMENT \'更改时间\'',
			'is_del' 						=> Schema::TYPE_SMALLINT . '(1) DEFAULT 0 COMMENT \'状态\'',
		],$tableOptions);
		$this->createIndex('couponrule_classify','{{%coupon_rule}}','couponrule_classify');
		$this->createIndex('couponrule_category','{{%coupon_rule}}','couponrule_category');
		$this->createIndex('couponrule_type','{{%coupon_rule}}','couponrule_type');
		$this->createIndex('couponrule_service_type_id','{{%coupon_rule}}','couponrule_service_type_id');
        $this->createIndex('couponrule_city_limit','{{%coupon_rule}}','couponrule_city_limit');
        $this->createIndex('couponrule_customer_type','{{%coupon_rule}}','couponrule_customer_type');
        $this->createIndex('couponrule_use_start_time','{{%coupon_rule}}','couponrule_use_start_time');
        $this->createIndex('couponrule_use_end_time','{{%coupon_rule}}','couponrule_use_end_time');
        $this->createIndex('couponrule_promote_type','{{%coupon_rule}}','`couponrule_promote_type`');
        $this->createIndex('is_disabled','{{%coupon_rule}}','`is_disabled`');
    }

    public function down(){
		$this->dropTable('{{%coupon_rule}}');
    }
}
