<?php

use yii\db\Schema;
use yii\db\Migration;

class m150923_141751_create_table_finance_refund_log extends Migration
{
    public function up()
    {

if ($this->db->driverName === 'mysql') {

			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'��ͷ��Ӧ��¼��\'';
			}
	$this->createTable('{{%finance_refund_log}}', [
	 'id' => Schema::TYPE_PK . '(10) AUTO_INCREMENT COMMENT \'����\'' ,
	 'finance_refund_id' => Schema::TYPE_INTEGER. '(8) DEFAULT NULL COMMENT \'��ͷ��id\'' ,
	  'finance_order_channel_id' => Schema::TYPE_SMALLINT . '(4) DEFAULT NULL COMMENT \'��������id\'' ,
	  'finance_order_channel_name' => Schema::TYPE_STRING . '(100) DEFAULT NULL COMMENT \'������������\'' ,
	  'finance_pay_channel_id' => Schema::TYPE_SMALLINT . '(4) DEFAULT NULL COMMENT \'֧������id\'' ,
	  'finance_pay_channel_name' => Schema::TYPE_STRING . '(100) DEFAULT NULL COMMENT \'֧����������\'' ,
	  'finance_refund_log_name' => Schema::TYPE_STRING . '(100)  DEFAULT NULL COMMENT \'��ͷ����\'' ,
	  'finance_refund_log_order_name' => Schema::TYPE_STRING . '(30) DEFAULT NULL COMMENT \'��Ӧ�Ķ���������(�����Ż��ȶԼ۸�����)\'' ,
	  'finance_refund_log_order_namebak1' => Schema::TYPE_STRING . '(20) DEFAULT \'0\' COMMENT \'��Ϊ��չ1�ֶ�\'' ,
	  'finance_refund_log_order_namebak2' => Schema::TYPE_STRING . '(20) DEFAULT \'0\' COMMENT \'��Ϊ��չ2�ֶ�\'' ,
	  'finance_refund_log_order_describe' => Schema::TYPE_STRING . '(50) DEFAULT \'0\' COMMENT \'����\'' ,
	  'create_time' => Schema::TYPE_INTEGER. '(10) DEFAULT NULL COMMENT \'����ʱ��\'' ,
	   'is_del' => Schema::TYPE_SMALLINT . '(1) DEFAULT \'0\' COMMENT \'0 ���� 1 ɾ��\'' ,
		 ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%finance_refund_log}}');

        return true;
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
