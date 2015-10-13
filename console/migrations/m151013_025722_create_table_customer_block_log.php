<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151013_025722_create_table_customer_block_log extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'客户黑名单操作记录表\'';
        }
        $this->createTable('{{%customer_block_log}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'编号\'' ,
            'customer_id' => Schema::TYPE_INTEGER . '(11) DEFAULT 0 COMMENT \'客户\'',
            'customer_block_log_status' => Schema::TYPE_INTEGER . '(4) DEFAULT 0 COMMENT \'状态，1为黑名单0为正常\'',
            'customer_block_log_reason' => Schema::TYPE_STRING . '(255) DEFAULT NULL COMMENT \'原因\'',
            'created_at'=>  Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'创建时间\'' ,
            'updated_at'=>  Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'更新时间\'' ,
            'is_del'=>  Schema::TYPE_SMALLINT.'(4) NOT NULL DEFAULT 0 COMMENT \'是否逻辑删除\'' ,
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%customer_block_log}}');
    }
}
