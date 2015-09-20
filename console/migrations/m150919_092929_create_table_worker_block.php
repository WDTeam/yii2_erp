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
            'worker_block_type' => Schema::TYPE_BOOLEAN . '(3) DEFAULT 0 COMMENT \'阿姨封号类型 0短期1永久\'',
            'worker_block_reason' => Schema::TYPE_STRING . '(16) DEFAULT NULL COMMENT \'阿姨封号原因\'',
            'worker_block_start' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'封号开始时间\'',
            'worker_block_finish' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'封号结束时间\'',
            'created_ad' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'创建时间\'',
            'updated_ad' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'最后更新时间\'',
            'admin_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'管理员id\'',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('ejj_worker_block');
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
