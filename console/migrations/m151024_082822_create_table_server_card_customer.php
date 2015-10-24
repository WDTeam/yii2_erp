<?php

use yii\db\Schema;
use yii\db\Migration;

class m151024_082822_create_table_server_card_customer extends Migration
{
    public function up()
    {
		$tableOptions = null;
		if($this->db->driverName === 'mysql'){
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'服务卡信息表\'';
		}
		
		$this->createTable('{{%server_card_customer}}',[
			'id'				=> Schema::TYPE_BIGPK .'(20) NOT NULL AUTO_INCREMENT COMMENT \'id\'',
			'order_id' 			=> Schema::TYPE_BIGINT.'(20) NOT NULL DEFAULT 0 COMMENT \'订单id\'',
			'order_code' 		=> Schema::TYPE_STRING.'(64) NOT NULL DEFAULT \'\' COMMENT \'订单编号\'',
			'card_id' 			=> Schema::TYPE_BIGINT.'(20) NOT NULL DEFAULT 0 COMMENT \'卡信息id\'',
			'card_no' 			=> Schema::TYPE_STRING.'(64) NOT NULL DEFAULT \'\' COMMENT \'卡号\'',
			'card_name' 		=> Schema::TYPE_STRING.'(64) NOT NULL DEFAULT \'\' COMMENT \'卡名\'',
			'card_type' 		=> Schema::TYPE_INTEGER.'(10) NOT NULL DEFAULT 0 COMMENT \'卡类型\'',
			'card_level' 		=> Schema::TYPE_INTEGER.'(10) NOT NULL DEFAULT 0 COMMENT \'卡级别\'',
			'pay_value' 		=> Schema::TYPE_DECIMAL.'(8,0) NOT NULL DEFAULT 0 COMMENT \'实收金额\'',
			'par_value' 		=> Schema::TYPE_DECIMAL.'(8,0) NOT NULL DEFAULT 0 COMMENT \'卡面金额\'',
			'reb_value' 		=> Schema::TYPE_DECIMAL.'(8,0) NOT NULL DEFAULT 0 COMMENT \'优惠金额\'',
			'res_value' 		=> Schema::TYPE_DECIMAL.'(8,0) NOT NULL DEFAULT 0 COMMENT \'余额\'',
			'customer_id' 		=> Schema::TYPE_INTEGER.'(8) NOT NULL DEFAULT 0 COMMENT \'持卡人id\'',
			'customer_name' 	=> Schema::TYPE_STRING.'(16) NOT NULL DEFAULT \'\' COMMENT \'持卡人名称\'',
			'customer_phone' 	=> Schema::TYPE_STRING.'(11) NOT NULL DEFAULT \'\' COMMENT \'持卡人手机号\'',
			'use_scope' 		=> Schema::TYPE_INTEGER.'(10) NOT NULL DEFAULT 0 COMMENT \'使用范围\'',
			'buy_at' 			=> Schema::TYPE_INTEGER.'(11) NOT NULL DEFAULT 0 COMMENT \'购买日期\'',
			'valid_at' 			=> Schema::TYPE_INTEGER.'(11) NOT NULL DEFAULT 0 COMMENT \'有效截止日期\'',
			'activated_at' 		=> Schema::TYPE_INTEGER.'(11) NOT NULL DEFAULT 0 COMMENT \'激活日期\'',
			'freeze_flag' 		=> Schema::TYPE_INTEGER.'(11) NOT NULL DEFAULT 0 COMMENT \'冻结标识\'',
			'created_at' 		=> Schema::TYPE_INTEGER.'(11) NOT NULL DEFAULT 0 COMMENT \'创建时间\'',
			'updated_at' 		=> Schema::TYPE_INTEGER.'(11) NOT NULL DEFAULT 0 COMMENT \'更改时间\'',
		]);
    }

    public function down()
    {
        echo "m151024_082822_create_table_server_card_customer cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
