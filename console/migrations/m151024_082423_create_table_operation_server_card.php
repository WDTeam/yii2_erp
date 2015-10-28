<?php

use yii\db\Schema;
use yii\db\Migration;

class m151024_082423_create_table_operation_server_card extends Migration
{
    public function up()
    {
		$tableOptions = null;
		if($this->db->driverName === 'mysql'){
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'服务卡信息表\'';
		}
		
		$this->createTable('{{%operation_server_card}}',[
			'id'=> Schema::TYPE_BIGPK .'(20) NOT NULL AUTO_INCREMENT COMMENT \'编号\'',
			'card_name' 	=> Schema::TYPE_STRING.'(64) NOT NULL DEFAULT \'\' COMMENT \'卡名\'',
			'card_type' 	=> Schema::TYPE_INTEGER.'(10) NOT NULL DEFAULT 0 COMMENT \'卡类型\'',
			'card_level'	 => Schema::TYPE_INTEGER.'(10) NOT NULL DEFAULT 0 COMMENT \'卡级别\'',
			'par_value' 	=> Schema::TYPE_DECIMAL.'(8,0) NOT NULL DEFAULT 0 COMMENT \'卡面金额\'',
			'reb_value' 	=> Schema::TYPE_DECIMAL.'(8,0) NOT NULL DEFAULT 0 COMMENT \'优惠金额\'',
			'use_scope' 	=> Schema::TYPE_INTEGER.'(10) NOT NULL DEFAULT 0 COMMENT \'使用范围\'',
			'valid_days' 	=> Schema::TYPE_INTEGER.'(10) NOT NULL DEFAULT 0 COMMENT \'有效时间(天)\'',
			'created_at' 	=> Schema::TYPE_INTEGER.'(11) NOT NULL DEFAULT 0 COMMENT \'创建时间\'',
			'updated_at' 	=> Schema::TYPE_INTEGER.'(11) NOT NULL DEFAULT 0 COMMENT \'更改时间\'',
			'is_softdel' => Schema::TYPE_SMALLINT . '(1) unsigned NOT NULL DEFAULT 1 COMMENT \'状态\'',
		]);

    }

    public function down()
    {
         $this->dropTable('{{%operation_server_card}}');

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
