<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_145909_create_table_pop_order_data extends Migration
{
	
	public function up()
    {
		if ($this->db->driverName === 'mysql') {
	    $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'���������˱�\'';

    	}
			$this->createTable('{{%pop_order_data}}', [
		  'id' => Schema::TYPE_PK . ' AUTO_INCREMENT COMMENT \'����id\'',
		  'pop_order_data_third_partynub' => Schema::TYPE_STRING . '(40) NOT NULL DEFAULT \'0\' COMMENT \'������������\'',
		  'order_channel_oid' => Schema::TYPE_SMALLINT. '(2) NOT NULL COMMENT \'�µ�����(��Ӧorder_channel)\'',
		  'pay_channel_pid' => Schema::TYPE_SMALLINT. '(2) NOT NULL COMMENT \'֧������(��Ӧpay_channel)\'',
		  'pop_order_data_tel' => Schema::TYPE_STRING . '(20) DEFAULT NULL COMMENT \'�û��绰\'',
		  'pop_order_data_worker_uid'  => Schema::TYPE_INTEGER . '(10) DEFAULT NULL COMMENT \'������\'',
		  'pop_order_data_plan_time'  => Schema::TYPE_INTEGER . '(10)  NOT NULL DEFAULT \'0\' COMMENT \'ԤԼ��ʼʱ��\'',
		  'pop_order_data_plan_counttime' => Schema::TYPE_SMALLINT. '(6) NOT NULL DEFAULT \'0\' COMMENT \'ԤԼ����ʱ��(�����Ӽ�¼)\'',
		  'pop_order_data_sum_money' => Schema::TYPE_DECIMAL. '(8,2) NOT NULL DEFAULT \'0.00\' COMMENT \'�ܽ��\'',
		  'pop_order_data_coupon_count' => Schema::TYPE_DECIMAL. '(6,2) NOT NULL DEFAULT \'0.00\' COMMENT \'�Żݾ�\'',
		  'pop_order_data_coupon_id'  => Schema::TYPE_INTEGER . '(8)  DEFAULT NULL COMMENT \'�Żݾ�id\'',
		  'pop_order_data_order2' => Schema::TYPE_STRING . '(40)  DEFAULT NULL COMMENT \'�Ӷ�����\'',

		  'pop_order_data_channel_order' => Schema::TYPE_STRING . '(40) DEFAULT NULL  COMMENT \'��ȡ����Ψһ������\'',
		  'pop_order_data_order_type' => Schema::TYPE_SMALLINT. '(2)  NOT NULL DEFAULT \'0\' COMMENT \'��������\'',
		  'pop_order_data_status' => Schema::TYPE_SMALLINT. '(2)   DEFAULT NULL COMMENT \'֧��״̬\'',
		  'pop_order_data_finance_isok' => Schema::TYPE_SMALLINT. '(1) DEFAULT NULL  COMMENT \'����ȷ��\'',
		  'pop_order_data_discount_pay' => Schema::TYPE_DECIMAL. '(8,2) NOT NULL  DEFAULT \'0.00\' COMMENT \'�Żݽ��\'',
		  'pop_order_data_reality_pay' => Schema::TYPE_DECIMAL. '(8,2) NOT NULL  DEFAULT \'0.00\' COMMENT \'ʵ���տ�\'',
		  'pop_order_data_order_time'  => Schema::TYPE_INTEGER . '(10) DEFAULT NULL  COMMENT \'�µ�ʱ��\'',
		  'pop_order_data_pay_time'  => Schema::TYPE_INTEGER . '(10) DEFAULT NULL  COMMENT \'֧��ʱ��\'',
		 
		  'pop_order_data_pay_status' => Schema::TYPE_SMALLINT. '(1)  NOT NULL  DEFAULT \'0\' COMMENT \'1 ���˳ɹ� 2 ����ȷ��ok  3 ����ȷ��on 4 ����δ����\'',
		  'pop_order_data_check_id' => Schema::TYPE_SMALLINT. '(3) DEFAULT NULL  COMMENT \'������id\'',
		  'pop_order_data_finance_time'  => Schema::TYPE_INTEGER . '(10) DEFAULT NULL COMMENT \'��������ύʱ��\'',
		  'addtime' => Schema::TYPE_INTEGER . '(10) DEFAULT NULL  COMMENT \'����ʱ��\'',
		  'is_del' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT \'0\' COMMENT \'0 ���� 1 ɾ��\'',
    			], $tableOptions);
    }

    public function down()
    {
          $this->dropTable('{{%pop_order_data}}');

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
