<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_145909_create_table_pop_order_data extends Migration
{
	
	public function up()
    {
		if ($this->db->driverName === 'mysql') {
	    $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'第三方对账表\'';

    	}
			$this->createTable('{{%pop_order_data}}', [
		  'id' => Schema::TYPE_PK . ' AUTO_INCREMENT COMMENT \'主键id\'',
		  'pop_order_data_third_partynub' => Schema::TYPE_STRING . '(40) NOT NULL DEFAULT \'0\' COMMENT \'第三方订单号\'',
		  'order_channel_oid' => Schema::TYPE_SMALLINT. '(2) NOT NULL COMMENT \'下单渠道(对应order_channel)\'',
		  'pay_channel_pid' => Schema::TYPE_SMALLINT. '(2) NOT NULL COMMENT \'支付渠道(对应pay_channel)\'',
		  'pop_order_data_tel' => Schema::TYPE_STRING . '(20) DEFAULT NULL COMMENT \'用户电话\'',
		  'pop_order_data_worker_uid'  => Schema::TYPE_INTEGER . '(10) DEFAULT NULL COMMENT \'服务阿姨\'',
		  'pop_order_data_plan_time'  => Schema::TYPE_INTEGER . '(10)  NOT NULL DEFAULT \'0\' COMMENT \'预约开始时间\'',
		  'pop_order_data_plan_counttime' => Schema::TYPE_SMALLINT. '(6) NOT NULL DEFAULT \'0\' COMMENT \'预约服务时长(按分钟记录)\'',
		  'pop_order_data_sum_money' => Schema::TYPE_DECIMAL. '(8,2) NOT NULL DEFAULT \'0.00\' COMMENT \'总金额\'',
		  'pop_order_data_coupon_count' => Schema::TYPE_DECIMAL. '(6,2) NOT NULL DEFAULT \'0.00\' COMMENT \'优惠卷\'',
		  'pop_order_data_coupon_id'  => Schema::TYPE_INTEGER . '(8)  DEFAULT NULL COMMENT \'优惠卷id\'',
		  'pop_order_data_order2' => Schema::TYPE_STRING . '(40)  DEFAULT NULL COMMENT \'子订单号\'',

		  'pop_order_data_channel_order' => Schema::TYPE_STRING . '(40) DEFAULT NULL  COMMENT \'获取渠道唯一订单号\'',
		  'pop_order_data_order_type' => Schema::TYPE_SMALLINT. '(2)  NOT NULL DEFAULT \'0\' COMMENT \'订单类型\'',
		  'pop_order_data_status' => Schema::TYPE_SMALLINT. '(2)   DEFAULT NULL COMMENT \'支付状态\'',
		  'pop_order_data_finance_isok' => Schema::TYPE_SMALLINT. '(1) DEFAULT NULL  COMMENT \'财务确定\'',
		  'pop_order_data_discount_pay' => Schema::TYPE_DECIMAL. '(8,2) NOT NULL  DEFAULT \'0.00\' COMMENT \'优惠金额\'',
		  'pop_order_data_reality_pay' => Schema::TYPE_DECIMAL. '(8,2) NOT NULL  DEFAULT \'0.00\' COMMENT \'实际收款\'',
		  'pop_order_data_order_time'  => Schema::TYPE_INTEGER . '(10) DEFAULT NULL  COMMENT \'下单时间\'',
		  'pop_order_data_pay_time'  => Schema::TYPE_INTEGER . '(10) DEFAULT NULL  COMMENT \'支付时间\'',
		 
		  'pop_order_data_pay_status' => Schema::TYPE_SMALLINT. '(1)  NOT NULL  DEFAULT \'0\' COMMENT \'1 对账成功 2 财务确定ok  3 财务确定on 4 财务未处理\'',
		  'pop_order_data_check_id' => Schema::TYPE_SMALLINT. '(3) DEFAULT NULL  COMMENT \'操作人id\'',
		  'pop_order_data_finance_time'  => Schema::TYPE_INTEGER . '(10) DEFAULT NULL COMMENT \'财务对账提交时间\'',
		  'addtime' => Schema::TYPE_INTEGER . '(10) DEFAULT NULL  COMMENT \'增加时间\'',
		  'is_del' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT \'0\' COMMENT \'0 正常 1 删除\'',
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
