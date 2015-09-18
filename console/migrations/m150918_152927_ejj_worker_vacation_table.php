<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_152927_ejj_worker_vacation_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'阿姨请假时间表\'';
        }
        $this->createTable('ejj_worker_vacation', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT COMMENT \'阿姨请假时间表自增id\'' ,
            'worker_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'主表阿姨id\'',
            'worker_vacation_start' => Schema::TYPE_INTEGER . '(10) DEFAULT NULL COMMENT \'请假开始时间\'',
            'worker_vacation_finish' => Schema::TYPE_INTEGER . '(10) DEFAULT NULL COMMENT \'请假结束时间\'',
            'worker_vacation_type' => Schema::TYPE_BOOLEAN. '(1) DEFAULT NULL COMMENT \'阿姨请假类型 1休假 2事假\'',
            'worker_vacation_extend' => Schema::TYPE_STRING . '(11) DEFAULT NULL COMMENT \'阿姨请假备注\'',
            'create_time' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'创建时间\'',
            'update_time' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'最后更新时间\'',
            'admin_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'操作管理员id\'',
        ], $tableOptions);
    }

    public function down()
    {
        echo "m150918_152927_ejj_worker_vacation_table cannot be reverted.\n";

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
