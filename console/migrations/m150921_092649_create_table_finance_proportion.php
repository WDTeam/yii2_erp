<?php

use yii\db\Schema;
use yii\db\Migration;

class m150921_092649_create_table_finance_proportion extends Migration
{
    public function up()
    {
		if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'第三方比例表\'';
        }
        $this->createTable('{{%finance_proportion}}', [
  'id' => Schema::TYPE_PK .' AUTO_INCREMENT COMMENT \'主键id\'' ,		
  'finance_proportion_period' => Schema::TYPE_STRING . '(100) DEFAULT NULL COMMENT \'周期\'' ,
  'finance_pay_channel_id' => Schema::TYPE_SMALLINT. '(2) DEFAULT NULL COMMENT \'支付渠道\'' ,
  'finance_pay_channel_name' => Schema::TYPE_STRING . '(100) DEFAULT NULL COMMENT \'渠道名称\'' ,
  'finance_order_channel_id' => Schema::TYPE_SMALLINT. '(2) DEFAULT NULL COMMENT \'订单渠道\'' ,
  'finance_order_channel_name' => Schema::TYPE_STRING . '(100) DEFAULT NULL COMMENT \'订单渠道名称\'' ,
  'finance_proportion_ratio' => Schema::TYPE_DECIMAL. '(4,2) DEFAULT \'1.00\' COMMENT \'比例(第三方对应比例)\'' ,
  'create_time' => Schema::TYPE_INTEGER . '(10) NOT NULL COMMENT \'退款申请时间\'' ,
  'is_del' => Schema::TYPE_SMALLINT. '(1) DEFAULT \'0\' COMMENT \'0 正常 1删除\'' ,
        ], $tableOptions);
    }


    public function down()
    {
        $this->dropTable('{{%finance_proportion}}');
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
