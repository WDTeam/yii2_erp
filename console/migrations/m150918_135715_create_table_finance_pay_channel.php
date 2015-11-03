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

        $this->execute(
		"INSERT INTO {{%finance_pay_channel}} VALUES ('7', '支付宝1jjtb', '7', '1', '1443339882', '0');
		INSERT INTO {{%finance_pay_channel}} VALUES ('8', '百度钱包', '8', '1', '1443339882', '0');
		INSERT INTO {{%finance_pay_channel}} VALUES ('10', '微信后台', '10', '1', '1443339882', '0');
		INSERT INTO {{%finance_pay_channel}} VALUES ('12', '银联后台', '12', '1', '1443339882', '0');
		INSERT INTO {{%finance_pay_channel}} VALUES ('13', '财付通', '13', '1', '1443339882', '0');"
        );

    }

    public function down()
    {
          $this->dropTable('{{%finance_pay_channel}}');

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
