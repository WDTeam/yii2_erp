<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151026_074743_create_table_order_complaint extends Migration
{
    public function safeUp()
    {
    	$tableOptions = null;
    	if($this->db->driverName === 'mysql'){
    		$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'订单投诉\'';
    	}
    	
    	$this->createTable('{{%order_complaint}}',[
    			'id'=>  Schema::TYPE_BIGPK.' NOT NULL AUTO_INCREMENT COMMENT \'ID\'',
    			'order_id'=>   Schema::TYPE_BIGINT.' NOT NULL COMMENT \'订单id\'',
    			'order_code_number' => Schema::TYPE_STRING.'(64) NOT NULL DEFAULT \'\' COMMENT \'订单流水号\'',
    			'complaint_code_number' => Schema::TYPE_STRING.'(64) NOT NULL DEFAULT \'\' COMMENT \'投诉流水号\'',
    			'complaint_type'=> Schema::TYPE_SMALLINT.'(2) DEFAULT 0 COMMENT \'投诉类型\'',
    			'complaint_status'=> Schema::TYPE_SMALLINT.'(2) DEFAULT 0 COMMENT \'投诉状态\'',
    			'complaint_channel'=> Schema::TYPE_SMALLINT.'(2) DEFAULT 0 COMMENT \'投诉渠道\'',
    			'complaint_section'=> Schema::TYPE_SMALLINT.'(2) DEFAULT 0 COMMENT \'投诉部门\'',
    			'complaint_assortment'=> Schema::TYPE_SMALLINT.'(3) DEFAULT 0 COMMENT \'投诉分类\'',
    			'complaint_level'=>  Schema::TYPE_STRING.'(2) DEFAULT NULL COMMENT \'投诉级别\'',
    			'complaint_phone' => Schema::TYPE_STRING.'(16) NOT NULL DEFAULT 0 COMMENT \'投诉手机号\'',
    			'complaint_content'=>  Schema::TYPE_TEXT. ' COMMENT \'投诉详情\'',
    			'complaint_time'=>  Schema::TYPE_INTEGER.'(11) NOT NULL DEFAULT 0 COMMENT \'投诉时间\'',
    			'created_at' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'创建时间\'',
    			'updated_at' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'修改时间\'',
    			'is_softdel' => Schema::TYPE_SMALLINT . '(1) unsigned NOT NULL DEFAULT 1 COMMENT \'状态\'',
    	],$tableOptions);
    }

    public function safeDown()
    {
    	$this->dropTable('{{%order_complaint}}');
    	
    	return true;
    }
}
