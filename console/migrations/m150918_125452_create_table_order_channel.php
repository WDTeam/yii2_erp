<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_125452_create_table_order_channel extends Migration
{
    public function up()
    {
$tableOptions = null;
       if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'订单渠道表\'';
        }
        $this->createTable('{{%order_channel}}', [
            'id' => Schema::TYPE_PK . '(5) AUTO_INCREMENT  COMMENT \'主键id\'' ,
            'order_channel_name' => Schema::TYPE_STRING . '(50) DEFAULT NULL COMMENT \'渠道名称\'',
            'order_channel_sort' => Schema::TYPE_SMALLINT . '(5) DEFAULT 1 COMMENT \'排序\'',
            'order_channel_is_lock' => Schema::TYPE_SMALLINT . '(1) DEFAULT \'1\' COMMENT \'1 上架 2 下架\'',
            'addtime' => Schema::TYPE_INTEGER. '(10) DEFAULT NULL COMMENT \'增加时间\'',
            'is_del' => Schema::TYPE_SMALLINT . '(1) DEFAULT \'0\' COMMENT \'0 正常 1 删除\'',
        ], $tableOptions);
        
        
	}

    public function down()
    {
        $this->dropTable('{{%order_channel}}');

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
