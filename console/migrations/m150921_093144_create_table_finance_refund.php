<?php

use yii\db\Schema;
use yii\db\Migration;

class m150921_093144_create_table_finance_refund extends Migration
{
    public function up()
    {
		if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'�˿����\'';
        }
        $this->createTable('{{%finance_refund}}' , [
  'id' => Schema::TYPE_PK .' AUTO_INCREMENT COMMENT \'����id\'' ,	
  'finance_refund_tel' => Schema::TYPE_STRING . '(20)  NOT NULL COMMENT \'�û��绰\'' ,
  'finance_refund_money' => Schema::TYPE_DECIMAL. '(8,2) DEFAULT NULL COMMENT \'�˿���\'' ,
  'finance_refund_stype' => Schema::TYPE_SMALLINT. '(2) NOT NULL COMMENT \'���뷽ʽ\'' ,
  'finance_refund_reason' => Schema::TYPE_STRING . '(255)  DEFAULT NULL COMMENT \'�˿�����\'' ,
  'finance_refund_discount' => Schema::TYPE_DECIMAL. '(6,2) DEFAULT NULL COMMENT \'�Żݼ۸�\'' ,
  'finance_refund_pay_create_time' => Schema::TYPE_INTEGER . '(10) DEFAULT NULL COMMENT \'����֧��ʱ��\'' ,
  'finance_pay_channel_id' => Schema::TYPE_SMALLINT. '(2) DEFAULT NULL COMMENT \'֧����ʽid\'' ,
  'finance_pay_channel_name' => Schema::TYPE_STRING . '(80) CHARACTER SET latin1 DEFAULT NULL COMMENT \'֧����ʽ����\'' ,
  'finance_refund_pay_flow_num' => Schema::TYPE_STRING . '(80) CHARACTER SET latin1 DEFAULT NULL COMMENT \'������\'' ,
  'finance_refund_pay_status' => Schema::TYPE_SMALLINT. '(2) DEFAULT NULL COMMENT \'֧��״̬ 1֧�� 0 δ֧�� 2 ����\'' ,
  'finance_refund_worker_id' => Schema::TYPE_INTEGER . '(10) DEFAULT NULL COMMENT \'������\'' ,
  'finance_refund_worker_tel' => Schema::TYPE_STRING . '(20) CHARACTER SET latin1 DEFAULT NULL COMMENT \'���̵绰\'' ,
  'create_time' => Schema::TYPE_INTEGER . '(10) NOT NULL COMMENT \'�˿�����ʱ��\'' ,
  'is_del' => Schema::TYPE_SMALLINT. '(1) DEFAULT \'0\' COMMENT \'0 ���� 1ɾ��\'' ,
        ], $tableOptions);
    }


    public function down()
    {
        $this->dropTable('{{%finance_refund}}');
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
