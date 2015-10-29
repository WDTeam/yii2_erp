<?php

use yii\db\Schema;
use yii\db\Migration;

class m151024_082845_create_table_operation_server_card_record extends Migration
{
    public function up()
    {
		$tableOptions = null;
		if($this->db->driverName === 'mysql'){
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'服务卡信息表\'';
		}
		
		$this->createTable('{{%operation_server_card_record}}',[
			'id'			=> Schema::TYPE_BIGPK .'(20) NOT NULL AUTO_INCREMENT COMMENT \'id\'',
			'trade_id' 		=> Schema::TYPE_BIGINT.'(20) NOT NULL DEFAULT 0 COMMENT \'交易id\'',
			'cus_card_id' 	=> Schema::TYPE_BIGINT.'(20) NOT NULL DEFAULT 0 COMMENT \'客户服务卡\'',
			'order_id' 		=> Schema::TYPE_BIGINT.'(20) NOT NULL DEFAULT 0 COMMENT \'订单id\'',
			'order_code' 	=> Schema::TYPE_STRING.'(20) NOT NULL DEFAULT \'\' COMMENT \'订单号\'',
			'card_no' 		=> Schema::TYPE_STRING.'(64) NOT NULL DEFAULT \'\' COMMENT \'服务卡号\'',
			'front_value' 	=> Schema::TYPE_DECIMAL.'(8,0) NOT NULL DEFAULT 0 COMMENT \'使用前金额\'',
			'behind_value' 	=> Schema::TYPE_DECIMAL.'(8,0) NOT NULL DEFAULT 0 COMMENT \'使用后金额\'',
			'consume_type' 	=> Schema::TYPE_INTEGER.'(2) NOT NULL DEFAULT 0 COMMENT \'服务类型\'',
			'business_type' => Schema::TYPE_INTEGER.'(2) NOT NULL DEFAULT 0 COMMENT \'业务类型\'',
			'use_value' 	=> Schema::TYPE_DECIMAL.'(8,0) NOT NULL DEFAULT 0 COMMENT \'使用金额\'',
			'created_at' 	=> Schema::TYPE_INTEGER.'(11) NOT NULL DEFAULT 0 COMMENT \'创建时间\'',
			'updated_at' 	=> Schema::TYPE_INTEGER.'(11) NOT NULL DEFAULT 0 COMMENT \'更改时间\'',
			'is_softdel' => Schema::TYPE_SMALLINT . '(1) unsigned NOT NULL DEFAULT 0 COMMENT \'状态\'',
		]);
    }

    public function down()
    {
        $this->dropTable('{{%operation_server_card_record}}');

        return true;
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
