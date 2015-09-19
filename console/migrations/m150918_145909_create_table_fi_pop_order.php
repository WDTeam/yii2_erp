<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_145909_create_table_fi_pop_order extends Migration
{
	
	public function up()
    {
		if ($this->db->driverName === 'mysql') {
	    $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'第三方订单对账记录表\'';

    	}
			$this->createTable('{{%fi_pop_order}}', [
		  'id' => Schema::TYPE_PK . ' AUTO_INCREMENT COMMENT \'主键id\'',
		  'fi_pop_order_number' => Schema::TYPE_STRING . '(40) NOT NULL DEFAULT \'0\' COMMENT \'第三方订单号\'',
		  'order_channel_id' => Schema::TYPE_SMALLINT. '(2) NOT NULL COMMENT \'下单渠道(对应order_channel)\'',
		  'order_channel_title' => Schema::TYPE_SMALLINT. '(80) NOT NULL COMMENT \'下单渠道名称(对应order_channel)\'',
		  'pay_channel_id' => Schema::TYPE_SMALLINT. '(2) NOT NULL COMMENT \'支付渠道(对应pay_channel)\'',
		  'pay_channel_title' => Schema::TYPE_SMALLINT. '(80) NOT NULL COMMENT \'支付渠道名称(对应pay_channel)\'',
		  'fi_pop_order_customer_tel' => Schema::TYPE_STRING . '(20) DEFAULT NULL COMMENT \'用户电话\'',
		  'fi_pop_order_worker_uid'  => Schema::TYPE_INTEGER . '(10) DEFAULT NULL COMMENT \'服务阿姨\'',
		  'fi_pop_order_booked_time'  => Schema::TYPE_INTEGER . '(10)  NOT NULL DEFAULT \'0\' COMMENT \'预约开始时间\'',
		  'fi_pop_order_booked_counttime' => Schema::TYPE_SMALLINT. '(6) NOT NULL DEFAULT \'0\' COMMENT \'预约服务时长(按分钟记录)\'',
		  'fi_pop_order_sum_money' => Schema::TYPE_DECIMAL. '(8,2) NOT NULL DEFAULT \'0.00\' COMMENT \'总金额\'',
		  'fi_pop_order_coupon_count' => Schema::TYPE_DECIMAL. '(6,2) NOT NULL DEFAULT \'0.00\' COMMENT \'优惠卷金额\'',
		  'fi_pop_order_coupon_id'  => Schema::TYPE_INTEGER . '(8)  DEFAULT NULL COMMENT \'优惠卷id\'',
		  'fi_pop_order_order2' => Schema::TYPE_STRING . '(40)  DEFAULT NULL COMMENT \'子订单号\'',
		  'fi_pop_order_channel_order' => Schema::TYPE_STRING . '(40) DEFAULT NULL  COMMENT \'获取渠道唯一订单号\'',
		  'fi_pop_order_order_type' => Schema::TYPE_SMALLINT. '(2)  NOT NULL DEFAULT \'0\' COMMENT \'订单类型\'',
		  'fi_pop_order_status' => Schema::TYPE_SMALLINT. '(2)   DEFAULT NULL COMMENT \'支付状态\'',
		  'fi_pop_order_finance_isok' => Schema::TYPE_SMALLINT. '(1) DEFAULT NULL  COMMENT \'财务确定\'',
		  'fi_pop_order_discount_pay' => Schema::TYPE_DECIMAL. '(8,2) NOT NULL  DEFAULT \'0.00\' COMMENT \'优惠金额\'',
		  'fi_pop_order_reality_pay' => Schema::TYPE_DECIMAL. '(8,2) NOT NULL  DEFAULT \'0.00\' COMMENT \'实际收款\'',
		  'fi_pop_order_order_time'  => Schema::TYPE_INTEGER . '(10) DEFAULT NULL  COMMENT \'下单时间\'',
		  'fi_pop_order_pay_time'  => Schema::TYPE_INTEGER . '(10) DEFAULT NULL  COMMENT \'支付时间\'',
		 
		  'fi_pop_order_pay_status' => Schema::TYPE_SMALLINT. '(1)  NOT NULL  DEFAULT \'0\' COMMENT \'1 对账成功 2 财务确定ok  3 财务确定on 4 财务未处理\'',
		  'fi_pop_order_pay_title' => Schema::TYPE_STRING. '(30)  NOT NULL  DEFAULT \'0\' COMMENT \'状态 描述\'',
		  'fi_pop_order_check_id' => Schema::TYPE_SMALLINT. '(3) DEFAULT NULL  COMMENT \'操作人id\'',
		  'fi_pop_order_finance_time'  => Schema::TYPE_INTEGER . '(10) DEFAULT NULL COMMENT \'财务对账提交时间\'',
		  'create_time' => Schema::TYPE_INTEGER . '(10) DEFAULT NULL  COMMENT \'增加时间\'',
		  'is_del' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT \'0\' COMMENT \'0 正常 1 删除\'',
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
