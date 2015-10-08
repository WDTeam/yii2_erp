<?php

use yii\db\Schema;
use yii\db\Migration;

class m150919_092929_create_table_worker_block extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'阿姨封号表\'';
        }
        $this->createTable('{{%worker_block}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT COMMENT \'阿姨封号表自增id\'' ,
            'worker_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'主表阿姨id\'',
            'worker_block_start_time' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'封号开始时间\'',
            'worker_block_finish_time' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'封号结束时间\'',
            'worker_block_reason'=>Schema::TYPE_STRING .'(255) DEFAULT NULL COMMENT \'封号原因\'',
            'worker_block_status' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'封号状态，0开启1关闭\'',
            'created_ad' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'创建时间\'',
            'updated_ad' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'最后更新时间\'',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%worker_block}}');
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
