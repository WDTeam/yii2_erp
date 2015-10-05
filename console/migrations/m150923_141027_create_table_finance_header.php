<?php

use yii\db\Schema;
use yii\db\Migration;

class m150923_141027_create_table_finance_header extends Migration
{
    public function up()
     {
    	if ($this->db->driverName === 'mysql') {

			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'第三方表头记录表\'';

			}

			$this->createTable('{{%finance_header}}', [
	'id' => Schema::TYPE_PK . '(10) AUTO_INCREMENT COMMENT \'主键\'' ,
	'finance_header_key' => Schema::TYPE_SMALLINT . '(2) DEFAULT NULL COMMENT \'对应栏位\'' ,
	'finance_header_title' => Schema::TYPE_STRING . '(100) DEFAULT NULL COMMENT \'当前名称\'' ,
  'finance_header_name' => Schema::TYPE_STRING . '(100) DEFAULT NULL COMMENT \'表头名称\'' ,
	'finance_order_channel_id' => Schema::TYPE_SMALLINT . '(4) DEFAULT NULL COMMENT \'订单渠道id\'' ,
	'finance_order_channel_name' => Schema::TYPE_STRING . '(100) DEFAULT NULL COMMENT \'订单渠道名称\'' ,
	'finance_pay_channel_id' => Schema::TYPE_SMALLINT . '(4) DEFAULT NULL COMMENT \'支付渠道id\'' ,
	'finance_pay_channel_name' => Schema::TYPE_STRING . '(100) DEFAULT NULL COMMENT \'支付渠道名称\'' ,
	'create_time' => Schema::TYPE_INTEGER. '(10) DEFAULT NULL COMMENT \'创建时间\'' ,
	'is_del' => Schema::TYPE_SMALLINT . '(1) DEFAULT \'0\' COMMENT \'0 正常 1 删除\'' ,
				 ], $tableOptions);
    }





    public function down()
    {
        $this->dropTable('{{%finance_header}}');

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
