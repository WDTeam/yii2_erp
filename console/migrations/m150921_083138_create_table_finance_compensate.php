<?php

use yii\db\Schema;
use yii\db\Migration;

class m150921_083138_create_table_finance_compensate extends Migration
{
     public function up()
    {

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'赔偿管理\'';
        }
        $this->createTable('{{%finance_compensate}}', [
  'id' => Schema::TYPE_PK .' AUTO_INCREMENT COMMENT \'主键id\'' ,
  'finance_compensate_oa_num' => Schema::TYPE_STRING . '(40) DEFAULT NULL COMMENT \'OA批号\'' ,
  'finance_compensate_pay_money' => Schema::TYPE_DECIMAL. '(8,2) DEFAULT NULL COMMENT \'支付金额\'' ,
  'finance_compensate_cause' => Schema::TYPE_STRING . '(150) DEFAULT NULL COMMENT \'赔偿原因\'' ,
  'finance_compensate_tel' => Schema::TYPE_STRING . '(20)  DEFAULT NULL COMMENT \'用户电话\'' ,
  'finance_compensate_money' => Schema::TYPE_DECIMAL. '(8,2) DEFAULT NULL COMMENT \'赔偿金额\'' ,
  'finance_pay_channel_id' => Schema::TYPE_SMALLINT. '(2) DEFAULT NULL COMMENT \'支付渠道id\'' ,
  'finance_pay_channel_name' => Schema::TYPE_STRING . '(60) CHARACTER SET latin1 DEFAULT NULL COMMENT \'支付渠道名称\'' ,
  'finance_order_channel_id' => Schema::TYPE_SMALLINT. '(2) DEFAULT NULL COMMENT \'订单渠道id\'' ,
  'finance_order_channel_name' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'订单渠道名称\'' ,
  'finance_compensate_discount' => Schema::TYPE_DECIMAL. '(6,2) DEFAULT NULL COMMENT \'优惠价格\'' ,
  'finance_compensate_pay_create_time' => Schema::TYPE_INTEGER . '(10) DEFAULT NULL COMMENT \'订单支付时间\'' ,
  'finance_compensate_pay_flow_num' => Schema::TYPE_STRING . '(80) CHARACTER SET latin1 DEFAULT NULL COMMENT \'订单号\'' ,
  'finance_compensate_pay_status' => Schema::TYPE_SMALLINT. '(2) DEFAULT NULL COMMENT \'支付状态 1支付 0 未支付 2 其他\'' ,
  'finance_compensate_worker_id' => Schema::TYPE_INTEGER.'(10) DEFAULT NULL COMMENT \'服务阿姨\'' ,
  'finance_compensate_worker_tel' => Schema::TYPE_STRING .'(20)  DEFAULT NULL COMMENT \'阿姨电话\'' ,
  'finance_compensate_proposer' => Schema::TYPE_STRING . '(20) DEFAULT NULL COMMENT \'申请人\'' ,
  'finance_compensate_auditor' => Schema::TYPE_STRING . '(20) DEFAULT NULL COMMENT \'审核人\'' ,
  'create_time' => Schema::TYPE_INTEGER . '(10) NOT NULL COMMENT \'退款申请时间\'' ,
  'is_del' => Schema::TYPE_SMALLINT. '(1) DEFAULT \'0\' COMMENT \'0 正常 1删除\'' ,
        ], $tableOptions);
    }


    public function down()
    {
        $this->dropTable('{{%finance_compensate}}');

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
