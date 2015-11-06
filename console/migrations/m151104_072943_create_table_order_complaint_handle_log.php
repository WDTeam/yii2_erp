<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151104_072943_create_table_order_complaint_handle_log extends Migration
{
 public function safeUp()

    {
    	$tableOptions = null;
    	if($this->db->driverName === 'mysql'){
    		$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'处理投诉日志\'';
    	}
    	 
    	$this->createTable('{{%order_complaint_handle_log}}',[
    			'id'=>  Schema::TYPE_BIGPK.' NOT NULL AUTO_INCREMENT COMMENT \'ID\'',
    			'order_complaint_id'=>Schema::TYPE_BIGINT.' NOT NULL DEFAULT 0 COMMENT \'投诉id\'',
    			'order_complaint_handle_id'=>Schema::TYPE_BIGINT.' NOT NULL DEFAULT 0 COMMENT \'投诉处理id\'',
    			'handle_operate'=> Schema::TYPE_STRING.'(15) NOT NULL DEFAULT \'\' COMMENT \'操作人\'',
    			'handle_option'=> Schema::TYPE_STRING.'(20) NOT NULL DEFAULT \'\' COMMENT \'操作项\'',
    			'status_before'=> Schema::TYPE_STRING.'(100) DEFAULT 0 COMMENT \'变更前\'',
    			'status_after' => Schema::TYPE_STRING.'(100) DEFAULT 0 COMMENT \'变更后\'',
    			'created_at' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'创建时间\'',
    			'updated_at' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'修改时间\'',
    			'is_softdel' => Schema::TYPE_SMALLINT.'(1) unsigned NOT NULL DEFAULT 0 COMMENT \'状态\'',
    	],$tableOptions);

    }



    public function safeDown()

    {
    	$this->dropTable('{{%order_complaint_handle_log}}');
    	
    	return true;

    }
	
}
