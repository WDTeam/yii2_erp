<?php

use yii\db\Schema;
use yii\db\Migration;

class m150923_141751_create_table_finance_refund_log extends Migration
{
    public function up()
    {

if ($this->db->driverName === 'mysql') {
    		
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'标头对应记录表\'';
			}
	$this->createTable('{{%finance_refund_log}}', [
	 'id' => Schema::TYPE_PK . '(10) AUTO_INCREMENT COMMENT \'主键\'' ,
	 'finance_refund_id' => Schema::TYPE_INTEGER. '(8) DEFAULT NULL COMMENT \'表头表id\'' ,
	  'finance_order_channel_id' => Schema::TYPE_SMALLINT . '(4) DEFAULT NULL COMMENT \'订单渠道id\'' ,
	  'finance_order_channel_name' => Schema::TYPE_STRING . '(100) DEFAULT NULL COMMENT \'订单渠道名称\'' ,
	  'finance_pay_channel_id' => Schema::TYPE_SMALLINT . '(4) DEFAULT NULL COMMENT \'支付渠道id\'' ,
	  'finance_pay_channel_name' => Schema::TYPE_STRING . '(100) DEFAULT NULL COMMENT \'支付渠道名称\'' ,
	  'finance_refund_log_name' => Schema::TYPE_STRING . '(100)  DEFAULT NULL COMMENT \'标头名称\'' ,
	  'finance_refund_log_order_name' => Schema::TYPE_STRING . '(30) DEFAULT NULL COMMENT \'对应的订单表名称(订单号或比对价格名称)\'' ,
	  'finance_refund_log_order_namebak1' => Schema::TYPE_STRING . '(20) DEFAULT \'0\' COMMENT \'作为扩展1字段\'' ,
	  'finance_refund_log_order_namebak2' => Schema::TYPE_STRING . '(20) DEFAULT \'0\' COMMENT \'作为扩展2字段\'' ,
	  'finance_refund_log_order_describe' => Schema::TYPE_STRING . '(50) DEFAULT \'0\' COMMENT \'描述\'' ,
	  'create_time' => Schema::TYPE_INTEGER. '(10) DEFAULT NULL COMMENT \'创建时间\'' ,
	   'is_del' => Schema::TYPE_SMALLINT . '(1) DEFAULT \'0\' COMMENT \'0 正常 1 删除\'' ,
		 ], $tableOptions);
		$this->createIndex('finance_order_channel_id','{{%finance_refund_log}}','finance_order_channel_id');
        $this->createIndex('finance_pay_channel_id','{{%finance_refund_log}}','finance_pay_channel_id');
    }

    public function down()
    {
        $this->dropTable('{{%finance_refund_log}}');
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
