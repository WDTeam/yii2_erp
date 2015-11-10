<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151028_101830_create_table_operation_service_card_consume_record extends Migration
{
    public function up()
    {
		$tableOptions = null;
		if($this->db->driverName === 'mysql'){
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'服务卡消费记录表\'';
		}
		
		$this->createTable('{{%operation_service_card_consume_record}}',[
			'id' => Schema::TYPE_BIGPK .'(20) NOT NULL AUTO_INCREMENT COMMENT \'id\'',
			'customer_id' => Schema::TYPE_BIGINT.'(20) NOT NULL DEFAULT 0 COMMENT \'用户id\'',
			'customer_trans_record_transaction_id' => Schema::TYPE_BIGINT.'(20) NOT NULL DEFAULT 0 COMMENT \'服务交易流水\'',
			'order_id' => Schema::TYPE_BIGINT.'(20) NOT NULL DEFAULT 0 COMMENT \'服务订单id\'',
			'order_code' => Schema::TYPE_STRING.'(20) NOT NULL DEFAULT \'\' COMMENT \'服务订单号\'',
			'service_card_with_customer_id' => Schema::TYPE_BIGINT.'(20) NOT NULL DEFAULT 0 COMMENT \'服务卡id\'',
			'service_card_with_customer_code' => Schema::TYPE_STRING.'(64) NOT NULL DEFAULT \'\' COMMENT \'服务卡号\'',
			'service_card_consume_record_front_money' => Schema::TYPE_DECIMAL.'(8,2) NOT NULL DEFAULT 0 COMMENT \'使用前金额\'',
			'service_card_consume_record_behind_money' => Schema::TYPE_DECIMAL.'(8,2) NOT NULL DEFAULT 0 COMMENT \'使用后金额\'',
			'service_card_consume_record_consume_type' => Schema::TYPE_INTEGER.'(2) NOT NULL DEFAULT 0 COMMENT \'服务类型\'',
			'service_card_consume_record_business_type' => Schema::TYPE_INTEGER.'(2) NOT NULL DEFAULT 0 COMMENT \'业务类型\'',
			'service_card_consume_record_use_money' => Schema::TYPE_DECIMAL.'(8,2) NOT NULL DEFAULT 0 COMMENT \'使用金额\'',
			'created_at' => Schema::TYPE_INTEGER.'(11) NOT NULL DEFAULT 0 COMMENT \'创建时间\'',
			'updated_at' => Schema::TYPE_INTEGER.'(11) NOT NULL DEFAULT 0 COMMENT \'更改时间\'',
			'is_del' => Schema::TYPE_SMALLINT . '(1) unsigned NOT NULL DEFAULT 0 COMMENT \'状态\'',
		],$tableOptions);
    }

    public function down()
    {
		$this->dropTable('{{%operation_service_card_consume_record}}');

        return true;
    }
}
