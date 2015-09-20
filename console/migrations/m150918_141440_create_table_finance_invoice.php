<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_141440_create_table_finance_invoice extends Migration
{
 public function up()
    {
    if ($this->db->driverName === 'mysql') {
    		$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'支付渠道表\'';
    	}
    	$this->createTable('{{%finance_invoice}}', [
    			'id' => Schema::TYPE_PK . '(10) AUTO_INCREMENT  COMMENT \'主键id\'' ,
    			'finance_invoice_serial_number' => Schema::TYPE_INTEGER . '(10) DEFAULT NULL  COMMENT \'流水号\'',
    			'finance_invoice_customer_tel' => Schema::TYPE_STRING . '(20)   COMMENT \'用户电话\'',
    			'finance_invoice_worker_tel' => Schema::TYPE_STRING . '(20)   COMMENT \'阿姨电话\'',
    			'pay_channel_pay_id' => Schema::TYPE_SMALLINT. '(6) DEFAULT NULL COMMENT \'支付方式\'',
			    'pay_channel_pay_title' => Schema::TYPE_STRING. '(200) DEFAULT NULL COMMENT \'支付名称\'',
    			'finance_invoice_pay_status' => Schema::TYPE_SMALLINT. '(1) DEFAULT NULL COMMENT \'支付状态\'',
    			'admin_confirm_uid' => Schema::TYPE_SMALLINT. '(4) DEFAULT NULL COMMENT \'确认人\'',
    			'finance_invoice_enrolment_time' => Schema::TYPE_INTEGER. '(10) DEFAULT NULL COMMENT \'申请时间\'',
    			'finance_invoice_money' => Schema::TYPE_DECIMAL. '(8,2) DEFAULT  \'0.00\' COMMENT \'发票金额\'',
    			'finance_invoice_title' => Schema::TYPE_STRING. '(100) DEFAULT NULL COMMENT \'发票抬头\'',
    			'finance_invoice_address' => Schema::TYPE_STRING. '(200) DEFAULT NULL COMMENT \'邮寄地址\'',
    			'finance_invoice_status' => Schema::TYPE_SMALLINT. '(1) NOT NULL DEFAULT \'0\' COMMENT \'0 未开发票 1已邮寄 2 未邮寄  3 上门取  4 审核中 5 审核通过 6已完成 7 已退回\'',
    			'finance_invoice_check_id' => Schema::TYPE_SMALLINT. '(4) DEFAULT NULL COMMENT \'审核人id\'',
    			'finance_invoice_number' => Schema::TYPE_SMALLINT. '(4) NOT NULL DEFAULT \'0\' COMMENT \'发票数量\'',
    			'finance_invoice_service_money' => Schema::TYPE_DECIMAL. '(8,2) NOT NULL DEFAULT \'0.00\' COMMENT \'开发票服务费\'',
    			'finance_invoice_corp_email' => Schema::TYPE_STRING. '(40) DEFAULT NULL COMMENT \'邮箱\'',
    			'finance_invoice_corp_address' => Schema::TYPE_STRING. '(200) DEFAULT NULL COMMENT \'公司地址\'',
    			'finance_invoice_corp_name' => Schema::TYPE_STRING. '(150) DEFAULT NULL COMMENT \'公司名称\'',
    			'finance_invoice_district_id' => Schema::TYPE_SMALLINT. '(4) DEFAULT NULL COMMENT \'城市id\'',
    			'classify_id' => Schema::TYPE_SMALLINT. '(4) NOT NULL COMMENT \'业务id\'',
			    'classify_title' => Schema::TYPE_SMALLINT. '(4) NOT NULL COMMENT \'业务title\'',
    			'create_time' => Schema::TYPE_STRING. '(10)  NOT NULL COMMENT \'增加时间\'',
    			'is_del' => Schema::TYPE_SMALLINT . '(1) DEFAULT \'0\' COMMENT \'0 正常 1 删除\'',
    			], $tableOptions);
    }

    public function down()
    {
          $this->dropTable('{{%finance_invoice}}');

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
