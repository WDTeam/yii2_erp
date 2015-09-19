<?php

use yii\db\Schema;
use yii\db\Migration;

class m150919_083803_create_table_fi_invoice_status extends Migration
{
    public function up()
    {

    if ($this->db->driverName === 'mysql') {
    		
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'发票状态表\'';
   
			}
    
			$this->createTable('{{%fi_invoice_status}}', [
		  'id' => Schema::TYPE_PK . '(10) AUTO_INCREMENT COMMENT \'主键\'' ,
		  'invoice_status_is_apply' => Schema::TYPE_SMALLINT . '(1) DEFAULT \'0\' COMMENT \'是否开发票 0 不可以 1 已开\'' ,
		  'invoice_status_is_apply_time' => Schema::TYPE_INTEGER. '(10) DEFAULT NULL COMMENT \'申请时间\'' ,
		  'invoice_status_post' => Schema::TYPE_SMALLINT . '(1) DEFAULT \'0\' COMMENT \'已邮寄 0未邮寄 1 已邮寄\'' ,
		  'invoice_status_post_time' => Schema::TYPE_INTEGER. '(10) DEFAULT NULL COMMENT \'邮寄时间\'' ,
		  'invoice_status_visiting_service' => Schema::TYPE_SMALLINT . '(1) DEFAULT \'0\' COMMENT \'上门取\'' ,
		  'invoice_status_visiting_service_time' => Schema::TYPE_INTEGER. '(10) DEFAULT NULL COMMENT \'上门取记录时间\'' ,
		  'invoice_status_auditing' => Schema::TYPE_SMALLINT . '(1) DEFAULT \'0\' COMMENT \'审核中0 未审核 1 审核中\'' ,
		  'invoice_status_auditing_time' => Schema::TYPE_INTEGER. '(10) DEFAULT NULL COMMENT \'审核初始时间\'' ,
		  'invoice_status_audit_ok' => Schema::TYPE_SMALLINT . '(1) DEFAULT \'0\' COMMENT \'审核通过 0 未通过 1 通过\'' ,
		  'invoice_status_audit_oktime' => Schema::TYPE_INTEGER. '(10) DEFAULT NULL COMMENT \'审核通过时间\'' ,
		  'invoice_status_accomplish' => Schema::TYPE_SMALLINT . '(1) DEFAULT \'0\' COMMENT \'发票已完成 0 未完成 1 完成\'' ,
		  'invoice_status_accomplish_time' => Schema::TYPE_INTEGER. '(10) DEFAULT NULL COMMENT \'完成时间\'' ,
		  'invoice_status_back' => Schema::TYPE_SMALLINT . '(1) DEFAULT NULL COMMENT \'发票已退回 0 未退回 1 退回\'' ,
		  'invoice_status_back_time' => Schema::TYPE_INTEGER. '(10) DEFAULT NULL COMMENT \'完成时间\'' ,
		  'create_time' => Schema::TYPE_INTEGER. '(10) DEFAULT NULL COMMENT \'创建时间\'' ,
		  'is_del' => Schema::TYPE_SMALLINT . '(1) DEFAULT \'0\' COMMENT \'0 正常 1 删除\'' ,
    			], $tableOptions);
    }

    public function down()
    {
          $this->dropTable('{{%fi_invoice_status}}');

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
