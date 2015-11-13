<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_145909_create_table_finance_pop_order extends Migration
{

	public function up()
    {
		if ($this->db->driverName === 'mysql') {
	    $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'第三方订单对账记录表\'';

    	}
			$this->createTable('{{%finance_pop_order}}', [
		  'id' => Schema::TYPE_PK . ' AUTO_INCREMENT COMMENT \'主键id\'',
			'order_code' => Schema::TYPE_STRING . '(64) NOT NULL DEFAULT \'暂无\' COMMENT \'系统订单号\'',
			'finance_pop_order_code' => Schema::TYPE_STRING . '(64) NOT NULL DEFAULT \'0\' COMMENT \'系统流水号\'',
			'order_status_name' => Schema::TYPE_STRING . '(128) NOT NULL DEFAULT \'无\' COMMENT \'订单状态\'',
			'order_money' => Schema::TYPE_DECIMAL . '(8,2) NOT NULL DEFAULT \'0.00\' COMMENT \'系统订单金额\'',
			'finance_record_log_id' => Schema::TYPE_STRING . '(40) NOT NULL DEFAULT \'0\' COMMENT \'账期对应表id\'',
		  'finance_pop_order_number' => Schema::TYPE_STRING . '(40) NOT NULL DEFAULT \'0\' COMMENT \'第三方订单号\'',
		  'finance_order_channel_id' => Schema::TYPE_SMALLINT. '(2) NOT NULL COMMENT \'下单渠道(对应finance_order_channel)\'',
		  'finance_order_channel_title' => Schema::TYPE_STRING. '(80) NOT NULL COMMENT \'下单渠道名称(对应order_channel)\'',
		  'finance_pay_channel_id' => Schema::TYPE_SMALLINT. '(2) NOT NULL COMMENT \'支付渠道(对应pay_channel)\'',
		  'finance_pay_channel_title' => Schema::TYPE_STRING. '(80) NOT NULL COMMENT \'支付渠道名称(对应pay_channel)\'',
		  'finance_pop_order_customer_tel' => Schema::TYPE_STRING . '(20) DEFAULT NULL COMMENT \'用户电话\'',
		  'finance_pop_order_worker_uid'  => Schema::TYPE_INTEGER . '(10) DEFAULT NULL COMMENT \'服务阿姨\'',
		  'finance_pop_order_booked_time'  => Schema::TYPE_INTEGER . '(10)  NOT NULL DEFAULT \'0\' COMMENT \'预约开始时间\'',
		  'finance_pop_order_booked_counttime' => Schema::TYPE_SMALLINT. '(6) NOT NULL DEFAULT \'0\' COMMENT \'预约服务时长(按分钟记录)\'',
		  'finance_pop_order_sum_money' => Schema::TYPE_DECIMAL. '(8,2) NOT NULL DEFAULT \'0.00\' COMMENT \'总金额\'',
		  'finance_pop_order_coupon_count' => Schema::TYPE_DECIMAL. '(6,2) NOT NULL DEFAULT \'0.00\' COMMENT \'优惠卷金额\'',
		  'finance_pop_order_coupon_id'  => Schema::TYPE_INTEGER . '(8)  DEFAULT NULL COMMENT \'优惠卷id\'',
		  'finance_pop_order_order2' => Schema::TYPE_STRING . '(40)  DEFAULT NULL COMMENT \'子订单号\'',
		  'finance_pop_order_channel_order' => Schema::TYPE_STRING . '(40) DEFAULT NULL  COMMENT \'获取渠道唯一订单号\'',
		  'finance_pop_order_order_type' => Schema::TYPE_SMALLINT. '(2)  NOT NULL DEFAULT \'0\' COMMENT \'订单类型\'',
		  'finance_pop_order_status' => Schema::TYPE_SMALLINT. '(2)   DEFAULT NULL COMMENT \'支付状态\'',
		  'finance_pop_order_finance_isok' => Schema::TYPE_SMALLINT. '(1) DEFAULT NULL  COMMENT \'财务确定\'',
		  'finance_pop_order_discount_pay' => Schema::TYPE_DECIMAL. '(8,2) NOT NULL  DEFAULT \'0.00\' COMMENT \'优惠金额\'',
		  'finance_pop_order_reality_pay' => Schema::TYPE_DECIMAL. '(8,2) NOT NULL  DEFAULT \'0.00\' COMMENT \'实际收款\'',
		  'finance_pop_order_order_time'  => Schema::TYPE_INTEGER . '(10) DEFAULT NULL  COMMENT \'下单时间\'',
		  'finance_pop_order_pay_time'  => Schema::TYPE_INTEGER . '(10) DEFAULT NULL  COMMENT \'支付时间\'',

		  'finance_pop_order_pay_status' => Schema::TYPE_SMALLINT. '(1)  NOT NULL  DEFAULT \'0\' COMMENT \'1 对账成功 2 财务确定ok  3 财务确定on 4 财务未处理\'',
		  'finance_pop_order_pay_title' => Schema::TYPE_STRING. '(30)  NOT NULL  DEFAULT \'0\' COMMENT \'状态 描述\'',
		  'finance_pop_order_check_id' => Schema::TYPE_SMALLINT. '(3) DEFAULT NULL  COMMENT \'操作人id\'',
		  'finance_pop_order_finance_time'  => Schema::TYPE_INTEGER . '(10) DEFAULT NULL COMMENT \'财务对账提交时间\'',
		 'finance_pop_order_pay_status_type'  => Schema::TYPE_SMALLINT . '(2) DEFAULT NULL COMMENT \'1 金额比对成功 2 三有我没有 3 我有三没有 4 金额比对失败 5 状态不对的\'',
		'finance_order_channel_statuspayment' => Schema::TYPE_INTEGER . '(10) DEFAULT NULL  COMMENT \'开始账期\'',
		'finance_order_channel_endpayment' => Schema::TYPE_INTEGER . '(10) DEFAULT NULL  COMMENT \'结束账期\'',
		'finance_pop_order_msg' => Schema::TYPE_STRING . '(100) DEFAULT NULL  COMMENT \'原因\'',
		'create_time' => Schema::TYPE_INTEGER . '(10) DEFAULT NULL  COMMENT \'增加时间\'',
	    'is_del' => Schema::TYPE_SMALLINT . '(1) NOT NULL DEFAULT \'0\' COMMENT \'0 正常 1 删除\'',
    			], $tableOptions);
		$this->createIndex('order_code','{{%finance_pop_order}}','order_code');
        $this->createIndex('finance_pop_order_code','{{%finance_pop_order}}','finance_pop_order_code');
        $this->createIndex('finance_order_channel_id','{{%finance_pop_order}}','finance_order_channel_id');
        $this->createIndex('finance_pop_order_customer_tel','{{%finance_pop_order}}','finance_pop_order_customer_tel');
        $this->createIndex('finance_pop_order_check_id','{{%finance_pop_order}}','finance_pop_order_check_id');
    }

    public function down()
    {
          $this->dropTable('{{%finance_pop_order}}');

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
