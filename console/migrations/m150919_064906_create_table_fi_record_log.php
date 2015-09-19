<?php

use yii\db\Schema;
use yii\db\Migration;

class m150919_064906_create_table_fi_record_log extends Migration
{

	public function up()
    {

    if ($this->db->driverName === 'mysql') {
    		
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'对账日志记录表\'';
   
			}
    
			$this->createTable('{{%fi_record_log}}', [
  'id' => Schema::TYPE_PK . '(10) AUTO_INCREMENT COMMENT \'主键\'' ,
'order_channel_id' => Schema::TYPE_SMALLINT . '(4) DEFAULT NULL COMMENT \'对账名称id\'' ,
  'order_channel_name' => Schema::TYPE_STRING . '(100) DEFAULT NULL COMMENT \'对账名称\'' ,
  'pay_channel_id' => Schema::TYPE_SMALLINT . '(4) DEFAULT NULL COMMENT \'收款渠道id\'' ,
  'pay_channel_name' => Schema::TYPE_STRING . '(100) DEFAULT NULL COMMENT \'收款渠道名称\'' ,
  'fi_record_log_succeed_count' => Schema::TYPE_SMALLINT . '(6) DEFAULT NULL COMMENT \'成功记录数\'' ,
  'fi_record_log_succeed_sum_money' => Schema::TYPE_DECIMAL. '(8,2) DEFAULT \'0.00\' COMMENT \'成功记录数总金额\'' ,
  'fi_record_log_manual_count' => Schema::TYPE_SMALLINT . '(6) DEFAULT NULL COMMENT \'人工确认笔数\'' ,
  'fi_record_log_manual_sum_money' => Schema::TYPE_DECIMAL. '(8,2) DEFAULT \'0.00\' COMMENT \'人工确认金额\'' ,
  'fi_record_log_failure_count' => Schema::TYPE_SMALLINT . '(6) DEFAULT \'0\' COMMENT \'失败笔数\'' ,
  'fi_record_log_failure_money' => Schema::TYPE_DECIMAL. '(8,2) DEFAULT \'0.00\' COMMENT \'失败总金额\'' ,
  'fi_record_log_confirm_name' => Schema::TYPE_STRING . '(30) DEFAULT NULL COMMENT \'对账人\'' ,
  'create_time' => Schema::TYPE_INTEGER. '(10) DEFAULT NULL COMMENT \'创建时间\'' ,
  'is_del' => Schema::TYPE_SMALLINT . '(1) DEFAULT \'0\' COMMENT \'0 正常 1 删除\'' ,
    			], $tableOptions);
    }

    public function down()
    {
          $this->dropTable('{{%fi_record_log}}');

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
