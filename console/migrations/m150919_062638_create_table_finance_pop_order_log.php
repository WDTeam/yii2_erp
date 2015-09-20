<?php

use yii\db\Schema;
use yii\db\Migration;

class m150919_062638_create_table_finance_pop_order_log extends Migration
{


 public function up()
    {
    	if ($this->db->driverName === 'mysql') {
    		
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'第三方订单号日志表\'';
   
			}
    
			$this->createTable('{{%finance_pop_order_log}}', [
  'id' => Schema::TYPE_PK . '(10) AUTO_INCREMENT COMMENT \'主键\'' ,
  'finance_pay_order_num' => Schema::TYPE_STRING . '(40) DEFAULT NULL COMMENT \'官方系统订单号\'' ,
  'finance_pop_order_number' => Schema::TYPE_STRING . '(40) DEFAULT NULL COMMENT \'第三方订单号\'' ,
  'finance_pop_order_log_series_succeed_status' => Schema::TYPE_SMALLINT . '(1) DEFAULT \'0\' COMMENT \'系统对账成功\'' ,
  'finance_pop_order_log_series_succeed_status_time' => Schema::TYPE_INTEGER. '(10) DEFAULT \'0\' COMMENT \'系统对账成功时间\'' ,
  'finance_pop_order_log_finance_status' => Schema::TYPE_SMALLINT . '(1) DEFAULT \'0\' COMMENT \'财务确定 \'' ,
  'finance_pop_order_log_finance_status_time' => Schema::TYPE_INTEGER. '(10) DEFAULT \'0\' COMMENT \'财务 1 失败\'' ,
  'finance_pop_order_log_finance_audit' => Schema::TYPE_SMALLINT . '(1) DEFAULT \'0\' COMMENT \'财务未处理\'' ,
  'finance_pop_order_log_finance_audit_time' => Schema::TYPE_INTEGER. '(10) DEFAULT \'0\' COMMENT \'财务未处理时间\'' ,
  'create_time' => Schema::TYPE_INTEGER. '(10) DEFAULT NULL COMMENT \'创建时间\'' ,
  'is_del' => Schema::TYPE_SMALLINT . '(1) DEFAULT \'0\' COMMENT \'0 正常 1 删除\'' ,
    			], $tableOptions);
    }

    public function down()
    {
          $this->dropTable('{{%finance_pop_order_log}}');

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
