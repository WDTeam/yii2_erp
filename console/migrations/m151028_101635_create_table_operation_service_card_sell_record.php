<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151028_101635_create_table_operation_service_card_sell_record extends Migration
{
    public function up()
    {
		$tableOptions = null;
		if($this->db->driverName === 'mysql'){
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'服务卡信息表\'';
		}
		
		$this->createTable('{{%operation_service_card_sell_record}}',[
			'id'		=> Schema::TYPE_BIGPK .'(20) NOT NULL AUTO_INCREMENT COMMENT \'id\'',
			'service_card_sell_record_code' 	=> Schema::TYPE_STRING.'(20) NOT NULL DEFAULT \'\' COMMENT \'购卡订单号\'',
			'customer_id' 						=> Schema::TYPE_BIGINT.'(20) NOT NULL DEFAULT 0 COMMENT \'用户id\'',
			'customer_phone'					=> Schema::TYPE_INTEGER.'(11) NOT NULL DEFAULT 0 COMMENT \'用户手机号\'',
			'service_card_info_id' 		=> Schema::TYPE_BIGINT.'(20) NOT NULL DEFAULT 0 COMMENT \'服务卡id\'',
			'service_card_info_name' 			=> Schema::TYPE_STRING.'(64) NOT NULL DEFAULT \'\' COMMENT \'服务卡名\'',
			'service_card_sell_record_money' 			=> Schema::TYPE_DECIMAL.'(8,2) NOT NULL DEFAULT 0 COMMENT \'购卡订单金额\'',
			'service_card_sell_record_channel_id' 		=> Schema::TYPE_INTEGER.'(10) NOT NULL DEFAULT 0 COMMENT \'购卡订单渠道id\'',
			'service_card_sell_record_channel_name' 	=> Schema::TYPE_STRING.'(64) NOT NULL DEFAULT \'\' COMMENT \'购卡订单渠道名称\'',
			'service_card_sell_record_status' 	=> Schema::TYPE_INTEGER.'(10) NOT NULL DEFAULT 0 COMMENT \'购卡订单状态\'',
			'customer_trans_record_pay_mode' 		=> Schema::TYPE_INTEGER.'(2) NOT NULL DEFAULT 0 COMMENT \'支付方式\'',
			'pay_channel_id' 		=> Schema::TYPE_INTEGER.'(10) NOT NULL DEFAULT 0 COMMENT \'支付渠道id\'',
			'customer_trans_record_pay_channel' 		=> Schema::TYPE_STRING.'(64) NOT NULL DEFAULT \'\' COMMENT \'支付渠道名称\'',
			'customer_trans_record_transaction_id' 	=> Schema::TYPE_STRING.'(255) NOT NULL DEFAULT \'\' COMMENT \'支付流水号\'',
			'customer_trans_record_pay_money' 		=> Schema::TYPE_DECIMAL.'(8,2) NOT NULL DEFAULT 0 COMMENT \'支付金额\'',
			'customer_trans_record_pay_account' 	=> Schema::TYPE_INTEGER.'(30) NOT NULL DEFAULT 0 COMMENT \'支付帐号\'',
			'customer_trans_record_paid_at' 				=> Schema::TYPE_INTEGER.'(11) NOT NULL DEFAULT 0 COMMENT \'支付时间\'',
			'created_at' 			=> Schema::TYPE_INTEGER.'(11) NOT NULL DEFAULT 0 COMMENT \'订单创建时间\'',
			'updated_at' 			=> Schema::TYPE_INTEGER.'(11) NOT NULL DEFAULT 0 COMMENT \'订单更改时间\'',
			'is_del' => Schema::TYPE_SMALLINT . '(1) unsigned NOT NULL DEFAULT 0 COMMENT \'状态\'',
		],$tableOptions);
    }

    public function down()
    {
		 $this->dropTable('{{%operation_service_card_sell_record}}');
    }
}
