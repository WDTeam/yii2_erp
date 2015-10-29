<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151026_093223_create_table_operation_server_card_order extends Migration
{
   public function up()
    {
		$tableOptions = null;
		if($this->db->driverName === 'mysql'){
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'服务卡信息表\'';
		}
		
		$this->createTable('{{%operation_server_card_order}}',[
			'id'					=> Schema::TYPE_BIGPK .'(20) NOT NULL AUTO_INCREMENT COMMENT \'id\'',
			'order_code' 			=> Schema::TYPE_STRING.'(20) NOT NULL DEFAULT \'\' COMMENT \'订单号\'',
			'usere_id' 				=> Schema::TYPE_BIGINT.'(20) NOT NULL DEFAULT 0 COMMENT \'用户id\'',
			'order_customer_phone'	=> Schema::TYPE_INTEGER.'(11) NOT NULL DEFAULT 0 COMMENT \'用户手机号\'',
			'server_card_id' 		=> Schema::TYPE_BIGINT.'(20) NOT NULL DEFAULT 0 COMMENT \'卡id\'',
			'card_name' 			=> Schema::TYPE_STRING.'(64) NOT NULL DEFAULT \'\' COMMENT \'卡名\'',
			'card_type' 			=> Schema::TYPE_INTEGER.'(10) NOT NULL DEFAULT 0 COMMENT \'卡类型\'',
			'card_level' 			=> Schema::TYPE_INTEGER.'(10) NOT NULL DEFAULT 0 COMMENT \'卡级别\'',
			'par_value' 			=> Schema::TYPE_DECIMAL.'(8,0) NOT NULL DEFAULT 0 COMMENT \'卡面值\'',
			'reb_value' 			=> Schema::TYPE_DECIMAL.'(8,0) NOT NULL DEFAULT 0 COMMENT \'卡优惠值\'',
			'order_money' 			=> Schema::TYPE_DECIMAL.'(8,0) NOT NULL DEFAULT 0 COMMENT \'订单金额\'',
			'order_src_id' 			=> Schema::TYPE_INTEGER.'(10) NOT NULL DEFAULT 0 COMMENT \'订单来源id\'',
			'order_src_name' 		=> Schema::TYPE_STRING.'(64) NOT NULL DEFAULT \'\' COMMENT \'订单来源名称\'',
			'order_channel_id' 		=> Schema::TYPE_INTEGER.'(10) NOT NULL DEFAULT 0 COMMENT \'订单渠道id\'',
			'order_channel_name' 	=> Schema::TYPE_STRING.'(64) NOT NULL DEFAULT \'\' COMMENT \'订单渠道名称\'',
			'order_lock_status' 	=> Schema::TYPE_SMALLINT.'(1) NOT NULL DEFAULT 0 COMMENT \'是否锁定\'',
			'order_status_id' 		=> Schema::TYPE_INTEGER.'(10) NOT NULL DEFAULT 0 COMMENT \'订单状态id\'',
			'order_status_name' 	=> Schema::TYPE_STRING.'(64) NOT NULL DEFAULT \'\' COMMENT \'订单状态名称\'',
			'created_at' 			=> Schema::TYPE_INTEGER.'(11) NOT NULL DEFAULT 0 COMMENT \'订单创建时间\'',
			'updated_at' 			=> Schema::TYPE_INTEGER.'(11) NOT NULL DEFAULT 0 COMMENT \'订单更改时间\'',
			'order_pay_type' 		=> Schema::TYPE_INTEGER.'(2) NOT NULL DEFAULT 0 COMMENT \'支付方式\'',
			'pay_channel_id' 		=> Schema::TYPE_INTEGER.'(10) NOT NULL DEFAULT 0 COMMENT \'支付渠道id\'',
			'pay_channel_name' 		=> Schema::TYPE_STRING.'(64) NOT NULL DEFAULT \'\' COMMENT \'支付渠道名称\'',
			'order_pay_flow_num' 	=> Schema::TYPE_STRING.'(255) NOT NULL DEFAULT \'\' COMMENT \'支付流水号\'',
			'order_pay_money' 		=> Schema::TYPE_DECIMAL.'(8,2) NOT NULL DEFAULT 0 COMMENT \'支付金额\'',
			'pay_account' 	=> Schema::TYPE_INTEGER.'(30) NOT NULL DEFAULT 0 COMMENT \'支付帐号\'',
			'paid_at' 				=> Schema::TYPE_INTEGER.'(11) NOT NULL DEFAULT 0 COMMENT \'支付时间\'',
			 'is_softdel' => Schema::TYPE_SMALLINT . '(1) unsigned NOT NULL DEFAULT 0 COMMENT \'状态\'',
		]);

    }

    public function down()
    {
         $this->dropTable('{{%operation_server_card_order}}');

        return true;
    }
}
