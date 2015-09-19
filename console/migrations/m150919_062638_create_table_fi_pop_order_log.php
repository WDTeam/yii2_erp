<?php

use yii\db\Schema;
use yii\db\Migration;

class m150919_062638_create_table_fi_pop_order_log extends Migration
{


 public function up()
    {
    	if ($this->db->driverName === 'mysql') {
    		
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'��������������־��\'';
   
			}
    
			$this->createTable('{{%fi_pop_order_log}}', [
  'id' => Schema::TYPE_PK . '(10) AUTO_INCREMENT COMMENT \'����\'' ,
  'pay_order_num' => Schema::TYPE_STRING . '(40) DEFAULT NULL COMMENT \'�ٷ�ϵͳ������\'' ,
  'fi_pop_order_number' => Schema::TYPE_STRING . '(40) DEFAULT NULL COMMENT \'������������\'' ,
  'fi_pop_order_log_series_succeed_status' => Schema::TYPE_SMALLINT . '(1) DEFAULT \'0\' COMMENT \'ϵͳ���˳ɹ�\'' ,
  'fi_pop_order_log_series_succeed_status_time' => Schema::TYPE_INTEGER. '(10) DEFAULT \'0\' COMMENT \'ϵͳ���˳ɹ�ʱ��\'' ,
  'fi_pop_order_log_finance_status' => Schema::TYPE_SMALLINT . '(1) DEFAULT \'0\' COMMENT \'����ȷ�� \'' ,
  'fi_pop_order_log_finance_status_time' => Schema::TYPE_INTEGER. '(10) DEFAULT \'0\' COMMENT \'���� 1 ʧ��\'' ,
  'fi_pop_order_log_finance_audit' => Schema::TYPE_SMALLINT . '(1) DEFAULT \'0\' COMMENT \'����δ����\'' ,
  'fi_pop_order_log_finance_audit_time' => Schema::TYPE_INTEGER. '(10) DEFAULT \'0\' COMMENT \'����δ����ʱ��\'' ,
  'create_time' => Schema::TYPE_INTEGER. '(10) DEFAULT NULL COMMENT \'����ʱ��\'' ,
  'is_del' => Schema::TYPE_SMALLINT . '(1) DEFAULT \'0\' COMMENT \'0 ���� 1 ɾ��\'' ,
    			], $tableOptions);
    }

    public function down()
    {
          $this->dropTable('{{%fi_pop_order_log}}');

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
