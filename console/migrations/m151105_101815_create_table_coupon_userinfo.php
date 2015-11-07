<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151105_101815_create_table_coupon_userinfo extends Migration
{
    public function safeUp()
    {
    	$tableOptions = null;
    	if($this->db->driverName === 'mysql'){
    		$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'优惠码用户绑定表\'';
    	}
    	
    	$this->createTable('{{%coupon_userinfo}}',[
    			'id'=>  Schema::TYPE_PK.' NOT NULL AUTO_INCREMENT COMMENT \'ID\'',
    			'customer_id'=>Schema::TYPE_INTEGER.' NOT NULL DEFAULT 0 COMMENT \'客户id\'',
    			'customer_tel'=>Schema::TYPE_STRING.'(11) NOT NULL DEFAULT 0 COMMENT \'客户手机号\'',
    			'coupon_userinfo_id'=> Schema::TYPE_INTEGER.'(8) NOT NULL DEFAULT 0 COMMENT \'优惠规则id\'',
    			'coupon_userinfo_code'=> Schema::TYPE_STRING.'(40) NOT NULL DEFAULT \'\' COMMENT \'优惠码\'',
    			'coupon_userinfo_name'=> Schema::TYPE_STRING.'(100) NOT NULL DEFAULT \'\' COMMENT \'优惠券名称\'',
    			'coupon_userinfo_price' => Schema::TYPE_DECIMAL.'(8,2) DEFAULT 0.00 COMMENT \'优惠券价值\'',
    			'coupon_userinfo_gettime' => Schema::TYPE_INTEGER.'(11) DEFAULT 0 COMMENT \'领取时间\'',
    			'coupon_userinfo_usetime' => Schema::TYPE_INTEGER.'(11) DEFAULT 0 COMMENT \'使用时间\'',
    			'coupon_userinfo_endtime' => Schema::TYPE_INTEGER.'(11) DEFAULT 0 COMMENT \'过期时间\'',
    			'order_code' => Schema::TYPE_STRING.'(64) DEFAULT 0 COMMENT \'如果已经使用订单号\'',
    			'system_user_id' => Schema::TYPE_INTEGER.'(4) DEFAULT 0 COMMENT \'绑定人id\'',
    			'system_user_name' => Schema::TYPE_STRING.'(40) DEFAULT \'\' COMMENT \'绑定人名称\'',
    			'is_used' => Schema::TYPE_SMALLINT.'(4) DEFAULT 0 COMMENT \'是否已经使用\'',
    			'created_at' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'创建时间\'',
    			'updated_at' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'修改时间\'',
    			'is_del' => Schema::TYPE_SMALLINT.'(1) unsigned NOT NULL DEFAULT 0 COMMENT \'状态\'',
    	],$tableOptions);
    }

    public function safeDown()
    {
    	$this->dropTable('{{%coupon_userinfo}}');
    	 
    	return true;
    }
}
