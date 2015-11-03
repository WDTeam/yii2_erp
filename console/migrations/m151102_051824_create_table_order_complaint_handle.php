<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151102_051824_create_table_order_complaint_handle extends Migration
{
	public function safeUp()
    {
    	$tableOptions = null;
    	if($this->db->driverName === 'mysql'){
    		$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'处理投诉\'';
    	}
    	
    	$this->createTable('{{%order_complaint_handle}}',[
    			'id'=>  Schema::TYPE_BIGPK.' NOT NULL AUTO_INCREMENT COMMENT \'ID\'',
    			'order_complaint_id'=>   Schema::TYPE_BIGINT.' NOT NULL COMMENT \'投诉id\'',
    			'handle_section'=> Schema::TYPE_SMALLINT.'(2) DEFAULT 0 COMMENT \'处理部门\'',
    			'handle_operate'=> Schema::TYPE_STRING.'(20) DEFAULT NULL COMMENT \'操作人\'',
    			'handle_plan'=>  Schema::TYPE_STRING.'(255) DEFAULT NULL COMMENT \'处理方案\'',
    			'created_at' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'创建时间\'',
    			'updated_at' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'修改时间\'',
    			'is_softdel' => Schema::TYPE_SMALLINT . '(1) unsigned NOT NULL DEFAULT 0 COMMENT \'状态\'',
    	]);
    }

    public function safeDown()
    {
    	$this->dropTable('{{%order_complaint_handle}}');
    	 
    	return true;
    }
	
}
