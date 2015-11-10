<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151028_101733_create_table_operation_service_card_with_customer extends Migration
{
    public function up()
    {
		$tableOptions = null;
		if($this->db->driverName === 'mysql'){
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'客户服务卡关系表\'';
		}
		
		$this->createTable('{{%operation_service_card_with_customer}}',[
			'id'				=> Schema::TYPE_BIGPK .'(20) NOT NULL AUTO_INCREMENT COMMENT \'id\'',
			'service_card_sell_record_id' 			=> Schema::TYPE_BIGINT.'(20) NOT NULL DEFAULT 0 COMMENT \'购卡销售记录id\'',
			'service_card_sell_record_code' 		=> Schema::TYPE_STRING.'(64) NOT NULL DEFAULT \'\' COMMENT \'购卡订单号\'',
			'server_card_info_id' 			=> Schema::TYPE_BIGINT.'(20) NOT NULL DEFAULT 0 COMMENT \'服务卡信息id\'',
			'service_card_with_customer_code' 			=> Schema::TYPE_STRING.'(64) NOT NULL DEFAULT \'\' COMMENT \'服务卡号\'',
			'server_card_info_name' 		=> Schema::TYPE_STRING.'(64) NOT NULL DEFAULT \'\' COMMENT \'服务卡卡名\'',
			'customer_trans_record_pay_money' 		=> Schema::TYPE_DECIMAL.'(8,2) NOT NULL DEFAULT 0 COMMENT \'实收金额\'',
			'server_card_info_value' 		=> Schema::TYPE_DECIMAL.'(8,2) NOT NULL DEFAULT 0 COMMENT \'卡面金额\'',
			'service_card_info_rebate_value' 		=> Schema::TYPE_DECIMAL.'(8,2) NOT NULL DEFAULT 0 COMMENT \'优惠金额\'',
			'service_card_with_customer_balance' 		=> Schema::TYPE_DECIMAL.'(8,2) NOT NULL DEFAULT 0 COMMENT \'余额\'',
			'customer_id' 		=> Schema::TYPE_INTEGER.'(8) NOT NULL DEFAULT 0 COMMENT \'持卡人id\'',
			'customer_phone' 	=> Schema::TYPE_STRING.'(11) NOT NULL DEFAULT \'\' COMMENT \'持卡人手机号\'',
			'server_card_info_scope' 		=> Schema::TYPE_INTEGER.'(10) NOT NULL DEFAULT 0 COMMENT \'使用范围\'',
			'service_card_with_customer_buy_at' 			=> Schema::TYPE_INTEGER.'(11) NOT NULL DEFAULT 0 COMMENT \'购买日期\'',
			'service_card_with_customer_valid_at' 			=> Schema::TYPE_INTEGER.'(11) NOT NULL DEFAULT 0 COMMENT \'有效截止日期\'',
			'service_card_with_customer_activated_at' 		=> Schema::TYPE_INTEGER.'(11) NOT NULL DEFAULT 0 COMMENT \'激活日期\'',
			'service_card_with_customer_status' 		=> Schema::TYPE_INTEGER.'(11) NOT NULL DEFAULT 0 COMMENT \'服务卡状态\'',
			'created_at' 		=> Schema::TYPE_INTEGER.'(11) NOT NULL DEFAULT 0 COMMENT \'创建时间\'',
			'updated_at' 		=> Schema::TYPE_INTEGER.'(11) NOT NULL DEFAULT 0 COMMENT \'更改时间\'',
			'is_del' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT 0 COMMENT \'状态\'',
		],$tableOptions);
		
    }

    public function down()
    {
		$this->dropTable('{{%operation_service_card_with_customer}}');
    }
}
