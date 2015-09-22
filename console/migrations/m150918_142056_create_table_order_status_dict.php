<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_142056_create_table_order_status_dict extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'订单状态表\'';
        }


        $this->createTable('{{%order_status_dict}}', [
            'id' => Schema::TYPE_PK . ' NOT NULL AUTO_INCREMENT COMMENT \'ID\'',
            'order_status_server_name' => Schema::TYPE_STRING . '(255) NOT NULL COMMENT \'状态名称服务端\'',
            'order_status_client_name' => Schema::TYPE_STRING . '(32) NOT NULL DEFAULT 0 COMMENT \'状态名称客户端\'',
            'created_at' => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL DEFAULT 0',
            'updated_at' => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL DEFAULT 0',
            'isdel' => Schema::TYPE_SMALLINT . '(1) unsigned NOT NULL DEFAULT 0',
        ], $tableOptions);

        $this->batchInsert('{{%order_status_dict}}',
            ['id', 'order_status_name', 'order_status_flow', 'order_status_operation', 'order_status_role', 'created_at', 'updated_at', 'isdel'],
            [
                ['1', '初始化','','','',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
            ]
        );
    }

    public function down()
    {
        $this->dropTable('{{%order_status_dict}}');
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
