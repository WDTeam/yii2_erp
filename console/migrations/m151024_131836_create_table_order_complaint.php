<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151024_131836_create_table_order_complaint extends Migration
{
    public function safeUp()
    {
    	$tableOptions = null;
    	if($this->db->driverName === 'mysql'){
    		$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'订单投诉\'';
    	}
    	
    	$this->createTable('{{%order_complaint}}',[
    			'id'=>  Schema::TYPE_PK.'(11) NOT NULL AUTO_INCREMENT COMMENT \'ID\'',
    			'order_id'=>  Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'订单id\'',
    			'worker_id' => Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'阿姨id\'',
    			'complaint_type'=> Schema::TYPE_SMALLINT.'(2) DEFAULT NULL COMMENT \'投诉类型\'',
    			'complaint_section'=> Schema::TYPE_SMALLINT.'(2) DEFAULT NULL COMMENT \'投诉部门\'',
    			'complaint_level'=>  Schema::TYPE_STRING.'(2) DEFAULT NULL COMMENT \'投诉级别\'',
    			'complaint_phone' => Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'投诉手机号\'',
    			'complaint_content'=>  Schema::TYPE_TEXT. ' COMMENT \'投诉详情\'',
    			'complaint_time'=>  Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'投诉时间\'',
    	]);
    }

    public function safeDown()
    {
    	$this->dropTable('{{%order_complaint}}');
    	
    	return true;
    }
}