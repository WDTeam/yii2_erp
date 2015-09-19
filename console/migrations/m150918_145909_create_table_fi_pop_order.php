<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_145909_create_table_fi_pop_order extends Migration
{
	
	public function up()
    {
		if ($this->db->driverName === 'mysql') {
	    $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'�������������˼�¼��\'';

    	}
			$this->createTable('{{%fi_pop_order}}', [
		  'id' => Schema::TYPE_PK . ' AUTO_INCREMENT COMMENT \'����id\'',
		  'fi_pop_order_number' => Schema::TYPE_STRING . '(40) NOT NULL DEFAULT \'0\' COMMENT \'������������\'',
		  'order_channel_id' => Schema::TYPE_SMALLINT. '(2) NOT NULL COMMENT \'�µ�����(��Ӧorder_channel)\'',
		  'order_channel_title' => Schema::TYPE_SMALLINT. '(80) NOT NULL COMMENT \'�µ���������(��Ӧorder_channel)\'',
		  'pay_channel_id' => Schema::TYPE_SMALLINT. '(2) NOT NULL COMMENT \'֧������(��Ӧpay_channel)\'',
		  'pay_channel_title' => Schema::TYPE_SMALLINT. '(80) NOT NULL COMMENT \'֧����������(��Ӧpay_channel)\'',
		  'fi_pop_order_tel' => Schema::TYPE_STRING . '(20) DEFAULT NULL COMMENT \'�û��绰\'',
		  'fi_pop_order_worker_uid'  => Schema::TYPE_INTEGER . '(10) DEFAULT NULL COMMENT \'������\'',
		  'fi_pop_order_booked_time'  => Schema::TYPE_INTEGER . '(10)  NOT NULL DEFAULT \'0\' COMMENT \'ԤԼ��ʼʱ��\'',
		  'fi_pop_order_booked_counttime' => Schema::TYPE_SMALLINT. '(6) NOT NULL DEFAULT \'0\' COMMENT \'ԤԼ����ʱ��(�����Ӽ�¼)\'',
		  'fi_pop_order_sum_money' => Schema::TYPE_DECIMAL. '(8,2) NOT NULL DEFAULT \'0.00\' COMMENT \'�ܽ��\'',
		  'fi_pop_order_coupon_count' => Schema::TYPE_DECIMAL. '(6,2) NOT NULL DEFAULT \'0.00\' COMMENT \'�Żݾ���\'',
		  'fi_pop_order_coupon_id'  => Schema::TYPE_INTEGER . '(8)  DEFAULT NULL COMMENT \'�Żݾ�id\'',
		  'fi_pop_order_order2' => Schema::TYPE_STRING . '(40)  DEFAULT NULL COMMENT \'�Ӷ�����\'',
		  'fi_pop_order_channel_order' => Schema::TYPE_STRING . '(40) DEFAULT NULL  COMMENT \'��ȡ����Ψһ������\'',
		  'fi_pop_order_order_type' => Schema::TYPE_SMALLINT. '(2)  NOT NULL DEFAULT \'0\' COMMENT \'��������\'',
		  'fi_pop_order_status' => Schema::TYPE_SMALLINT. '(2)   DEFAULT NULL COMMENT \'֧��״̬\'',
		  'fi_pop_order_finance_isok' => Schema::TYPE_SMALLINT. '(1) DEFAULT NULL  COMMENT \'����ȷ��\'',
		  'fi_pop_order_discount_pay' => Schema::TYPE_DECIMAL. '(8,2) NOT NULL  DEFAULT \'0.00\' COMMENT \'�Żݽ��\'',
		  'fi_pop_order_reality_pay' => Schema::TYPE_DECIMAL. '(8,2) NOT NULL  DEFAULT \'0.00\' COMMENT \'ʵ���տ�\'',
		  'fi_pop_order_order_time'  => Schema::TYPE_INTEGER . '(10) DEFAULT NULL  COMMENT \'�µ�ʱ��\'',
		  'fi_pop_order_pay_time'  => Schema::TYPE_INTEGER . '(10) DEFAULT NULL  COMMENT \'֧��ʱ��\'',
		 
		  'fi_pop_order_pay_status' => Schema::TYPE_SMALLINT. '(1)  NOT NULL  DEFAULT \'0\' COMMENT \'1 ���˳ɹ� 2 ����ȷ��ok  3 ����ȷ��on 4 ����δ����\'',
		  'fi_pop_order_pay_title' => Schema::TYPE_STRING. '(30)  NOT NULL  DEFAULT \'0\' COMMENT \'״̬ ����\'',
		  'fi_pop_order_check_id' => Schema::TYPE_SMALLINT. '(3) DEFAULT NULL  COMMENT \'������id\'',
		  'fi_pop_order_finance_time'  => Schema::TYPE_INTEGER . '(10) DEFAULT NULL COMMENT \'��������ύʱ��\'',
		  'create_time' => Schema::TYPE_INTEGER . '(10) DEFAULT NULL  COMMENT \'����ʱ��\'',
		  'is_del' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT \'0\' COMMENT \'0 ���� 1 ɾ��\'',
    			], $tableOptions);
    }

    public function down()
    {
          $this->dropTable('{{%fi_pop_order}}');

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
