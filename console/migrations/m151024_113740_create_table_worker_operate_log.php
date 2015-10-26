<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151024_113740_create_table_worker_operate_log extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'阿姨操作日志表\'';
        }
        $this->createTable('{{%worker_operate_log}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT COMMENT \'阿姨操作日志id\'' ,
            'worker_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'阿姨id\'',
            'worker_operate_type' => Schema::TYPE_INTEGER. '(2) DEFAULT NULL COMMENT \'操作类型 1阿姨信息操作2阿姨工作时间操作3阿姨审核操作4阿姨封号操作5阿姨请假操作6阿姨黑名单操作7阿姨离职操作\'',
            'worker_operate_admin_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'操作管理员id  0系统操作(到达解封时间，系统自动解封)\'',
            'worker_operate_bak'=>Schema::TYPE_STRING .'(255) DEFAULT NULL COMMENT \'操作说明\'',
            'worker_operate_time' => Schema::TYPE_INTEGER . '(10) DEFAULT NULL COMMENT \'操作时间\'',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%worker_operate_log}}');

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
