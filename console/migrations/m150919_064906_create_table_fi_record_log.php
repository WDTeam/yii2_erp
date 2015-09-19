<?php

use yii\db\Schema;
use yii\db\Migration;

class m150919_064906_create_table_fi_record_log extends Migration
{

	public function up()
    {

    if ($this->db->driverName === 'mysql') {
    		
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'������־��¼��\'';
   
			}
    
			$this->createTable('{{%fi_record_log}}', [
  'id' => Schema::TYPE_PK . '(10) AUTO_INCREMENT COMMENT \'����\'' ,
'order_channel_id' => Schema::TYPE_SMALLINT . '(4) DEFAULT NULL COMMENT \'��������id\'' ,
  'order_channel_name' => Schema::TYPE_STRING . '(100) DEFAULT NULL COMMENT \'��������\'' ,
  'pay_channel_id' => Schema::TYPE_SMALLINT . '(4) DEFAULT NULL COMMENT \'�տ�����id\'' ,
  'pay_channel_name' => Schema::TYPE_STRING . '(100) DEFAULT NULL COMMENT \'�տ���������\'' ,
  'fi_record_log_succeed_count' => Schema::TYPE_SMALLINT . '(6) DEFAULT NULL COMMENT \'�ɹ���¼��\'' ,
  'fi_record_log_succeed_sum_money' => Schema::TYPE_DECIMAL. '(8,2) DEFAULT \'0.00\' COMMENT \'�ɹ���¼���ܽ��\'' ,
  'fi_record_log_manual_count' => Schema::TYPE_SMALLINT . '(6) DEFAULT NULL COMMENT \'�˹�ȷ�ϱ���\'' ,
  'fi_record_log_manual_sum_money' => Schema::TYPE_DECIMAL. '(8,2) DEFAULT \'0.00\' COMMENT \'�˹�ȷ�Ͻ��\'' ,
  'fi_record_log_failure_count' => Schema::TYPE_SMALLINT . '(6) DEFAULT \'0\' COMMENT \'ʧ�ܱ���\'' ,
  'fi_record_log_failure_money' => Schema::TYPE_DECIMAL. '(8,2) DEFAULT \'0.00\' COMMENT \'ʧ���ܽ��\'' ,
  'fi_record_log_confirm_name' => Schema::TYPE_STRING . '(30) DEFAULT NULL COMMENT \'������\'' ,
  'create_time' => Schema::TYPE_INTEGER. '(10) DEFAULT NULL COMMENT \'����ʱ��\'' ,
  'is_del' => Schema::TYPE_SMALLINT . '(1) DEFAULT \'0\' COMMENT \'0 ���� 1 ɾ��\'' ,
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
