<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151028_101442_create_table_operation_service_card_info extends Migration
{
    public function up()
    {
		
		
		$tableOptions = null;
		if($this->db->driverName === 'mysql'){
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'服务卡信息表\'';
		}
		
		$this->createTable('{{%operation_service_card_info}}',[
			'id'=> Schema::TYPE_BIGPK .'(20) NOT NULL AUTO_INCREMENT COMMENT \'id\'',
			'service_card_info_name' 	=> Schema::TYPE_STRING.'(64) NOT NULL DEFAULT \'\' COMMENT \'卡名\'',
			'service_card_info_type' 	=> Schema::TYPE_INTEGER.'(10) NOT NULL DEFAULT 0 COMMENT \'卡类型\'',
			'service_card_info_level'	 => Schema::TYPE_INTEGER.'(10) NOT NULL DEFAULT 0 COMMENT \'卡级别\'',
			'service_card_info_value' 	=> Schema::TYPE_DECIMAL.'(8,2) NOT NULL DEFAULT 0 COMMENT \'卡面金额\'',
			'service_card_info_rebate_value' 	=> Schema::TYPE_DECIMAL.'(8,2) NOT NULL DEFAULT 0 COMMENT \'优惠金额\'',
			'service_card_info_scope' 	=> Schema::TYPE_INTEGER.'(10) NOT NULL DEFAULT 0 COMMENT \'使用范围\'',
			'service_card_info_valid_days' 	=> Schema::TYPE_INTEGER.'(20) NOT NULL DEFAULT 0 COMMENT \'有效时间(天)\'',
			'created_at' 	=> Schema::TYPE_INTEGER.'(11) NOT NULL DEFAULT 0 COMMENT \'创建时间\'',
			'updated_at' 	=> Schema::TYPE_INTEGER.'(11) NOT NULL DEFAULT 0 COMMENT \'更改时间\'',
			'is_del' => Schema::TYPE_SMALLINT . '(1) unsigned NOT NULL DEFAULT 0 COMMENT \'状态\'',
		],$tableOptions);
    }

    public function down()
    {
		$this->dropTable('{{%operation_service_card_info}}');
    }
}
