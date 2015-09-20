<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_135715_create_table_finance_pay_channel extends Migration
{
    public function up()
    {
    	if ($this->db->driverName === 'mysql') {
    		
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'支付渠道表\'';
   
			}
    
			$this->createTable('{{%finance_pay_channel}}', [
    		
			'id' => Schema::TYPE_PK . '(5) AUTO_INCREMENT  COMMENT \'主键id\'' ,
    			'finance_pay_channel_name' => Schema::TYPE_STRING . '(50) DEFAULT NULL COMMENT \'渠道名称\'',
    	'finance_pay_channel_rank' => Schema::TYPE_SMALLINT . '(5) DEFAULT 1 COMMENT \'排序\'',
    			'finance_pay_channel_is_lock' => Schema::TYPE_SMALLINT . '(1) DEFAULT \'1\' COMMENT \'1 上架 2 下架\'',
    
			'create_time' => Schema::TYPE_INTEGER. '(10) DEFAULT NULL COMMENT \'增加时间\'',
    		'is_del' => Schema::TYPE_SMALLINT . '(1) DEFAULT \'0\' COMMENT \'0 正常 1 删除\'',
    			], $tableOptions);
    }

    public function down()
    {
          $this->dropTable('{{%finance_pay_channel}}');

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
