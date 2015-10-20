<?php

use yii\db\Schema;
use yii\db\Migration;

class m150921_093144_create_table_finance_refund extends Migration
{
    public function up()
    {
		if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'退款管理\'';
        }
        $this->createTable('{{%finance_refund}}' , [
  'id' => Schema::TYPE_PK .' AUTO_INCREMENT COMMENT \'主键id\'' ,
  'finance_refund_pop_nub' => Schema::TYPE_STRING . '(40)  NOT NULL COMMENT \'第三方订单号\'' ,
  'finance_refund_tel' => Schema::TYPE_STRING . '(20)  NOT NULL COMMENT \'用户电话\'' ,
  'finance_refund_money' => Schema::TYPE_DECIMAL. '(8,2) DEFAULT NULL COMMENT \'退款金额\'' ,
  'finance_refund_stype' => Schema::TYPE_SMALLINT. '(2) NOT NULL COMMENT \'申请方式\'' ,
  'finance_refund_reason' => Schema::TYPE_STRING . '(255)  DEFAULT NULL COMMENT \'退款理由\'' ,
  'finance_refund_discount' => Schema::TYPE_DECIMAL. '(6,2) DEFAULT NULL COMMENT \'优惠价格\'' ,
  'finance_refund_pay_create_time' => Schema::TYPE_INTEGER . '(10) DEFAULT NULL COMMENT \'订单支付时间\'' ,
  'finance_pay_channel_id' => Schema::TYPE_SMALLINT. '(2) DEFAULT NULL COMMENT \'支付方式id\'' ,
  'finance_pay_channel_title' => Schema::TYPE_STRING . '(80) CHARACTER SET latin1 DEFAULT NULL COMMENT \'支付方式名称\'' ,
  'finance_refund_pay_flow_num' => Schema::TYPE_STRING . '(80) CHARACTER SET latin1 DEFAULT NULL COMMENT \'订单号\'' ,
  'finance_refund_pay_status' => Schema::TYPE_SMALLINT. '(2) DEFAULT NULL COMMENT \'支付状态 1支付 0 未支付 2 其他\'' ,
  'finance_refund_worker_id' => Schema::TYPE_INTEGER . '(10) DEFAULT NULL COMMENT \'服务阿姨\'' ,
  'finance_refund_worker_tel' => Schema::TYPE_STRING . '(20) CHARACTER SET latin1 DEFAULT NULL COMMENT \'阿姨电话\'' ,
  'finance_order_channel_id' => Schema::TYPE_SMALLINT . '(5) CHARACTER SET latin1 DEFAULT NULL COMMENT \'订单渠道id\'' ,
   'finance_order_channel_title' => Schema::TYPE_STRING . '(30) CHARACTER SET latin1 DEFAULT NULL COMMENT \'订单渠道名称\'' ,
	'isstatus' => Schema::TYPE_SMALLINT . '(2) NOT NULL COMMENT \'1 取消 2 退款的 3 财务已经审核 4 财务已经退款\'' ,
  'create_time' => Schema::TYPE_INTEGER . '(10) NOT NULL COMMENT \'退款申请时间\'' ,
  'is_del' => Schema::TYPE_SMALLINT. '(1) DEFAULT \'0\' COMMENT \'0 正常 1删除\'' ,
        ], $tableOptions);
    }


    public function down()
    {
        $this->dropTable('{{%finance_refund}}');

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
