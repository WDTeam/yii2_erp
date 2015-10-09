<?php

use yii\db\Schema;
use yii\db\Migration;

class m150921_101649_create_table_worker_block_log extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'阿姨封号表\'';
        }
        $this->createTable('{{%worker_block_log}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT COMMENT \'阿姨封号表日志id\'' ,
            'worker_id',Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'阿姨id\'',
            'worker_block_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'封号表id\'',
            'worker_block_operate_type' => Schema::TYPE_INTEGER. '(2) DEFAULT NULL COMMENT \'操作类型 1创建阿姨封号2缩短封号时间3延长封号时间4关闭封号\'',
            'worker_block_operate_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'操作管理员id  0系统操作(到达解封时间，系统自动解封)\'',
            'worker_block_operate_bak'=>Schema::TYPE_STRING .'(255) DEFAULT NULL COMMENT \'操作备注\'',
            'worker_block_operate_time' => Schema::TYPE_INTEGER . '(10) DEFAULT NULL COMMENT \'操作时间\'',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%worker_block_log}}');

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
