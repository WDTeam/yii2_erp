<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151105_101442_create_table_order_dispatcher_kpi extends Migration
{
    public function up()
    {
		
		
		$tableOptions = null;
		if($this->db->driverName === 'mysql'){
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'服务卡信息表\'';
		}
		
		$this->createTable('{{%order_dispatcher_kpi}}',[
			'id'=> Schema::TYPE_BIGPK .'(20) NOT NULL AUTO_INCREMENT COMMENT \'id\'',
			'system_user_id' 	=>Schema::TYPE_INTEGER.'(20) NOT NULL DEFAULT 0 COMMENT \'用户ID\'',
			'system_user_name' 	=>Schema::TYPE_STRING.'(64) NOT NULL DEFAULT \'\' COMMENT \'用户名\'',
			'dispatcher_kpi_date'	 => Schema::TYPE_INTEGER.'(11) NOT NULL DEFAULT 0 COMMENT \'考核日期\'',
			'dispatcher_kpi_free_time' 	=>Schema::TYPE_INTEGER.'(11) NOT NULL DEFAULT 0 COMMENT \'空闲时间\'',
			'dispatcher_kpi_busy_time' 	=> Schema::TYPE_INTEGER.'(11) NOT NULL DEFAULT 0 COMMENT \'忙碌时间\'',
			'dispatcher_kpi_rest_time' 	=> Schema::TYPE_INTEGER.'(11) NOT NULL DEFAULT 0 COMMENT \'小休时间\'',
			'dispatcher_kpi_end_at' 	=> Schema::TYPE_INTEGER.'(11) NOT NULL DEFAULT 0 COMMENT \'收工时间\'',
			'dispatcher_kpi_obtain_count' 	=>Schema::TYPE_INTEGER.'(11) NOT NULL DEFAULT 0 COMMENT \'获得派单数\'',
			'dispatcher_kpi_assigned_count' 	=> Schema::TYPE_INTEGER.'(11) NOT NULL DEFAULT 0 COMMENT \'成功派单数\'',
			'dispatcher_kpi_assigned_rate' 	=> Schema::TYPE_FLOAT.'(2,5) NOT NULL DEFAULT 0 COMMENT \'成功派单比率\'',
			'dispatcher_kpi_status' 	=> Schema::TYPE_INTEGER.'(2) NOT NULL DEFAULT 0 COMMENT \'数据状态\'',
			'created_at' 	=> Schema::TYPE_INTEGER.'(11) NOT NULL DEFAULT 0 COMMENT \'创建时间\'',
			'updated_at' 	=> Schema::TYPE_INTEGER.'(11) NOT NULL DEFAULT 0 COMMENT \'更改时间\'',
			'is_del' => Schema::TYPE_SMALLINT . '(1) unsigned NOT NULL DEFAULT 0 COMMENT \'状态\'',
		],$tableOptions);
    }

    public function down()
    {
		$this->dropTable('{{%order_dispatcher_kpi}}');
    }
}
