<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_153312_ejj_worker_work_time_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'阿姨工作安排表\'';
        }
        $this->createTable('ejj_worker_work_time', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT COMMENT \'阿姨工作安排表自增id\'' ,
            'worker_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'主表阿姨id\'',
            'worker_work_start_hour' => Schema::TYPE_INTEGER . '(10) DEFAULT NULL COMMENT \'开始的小时\'',
            'worker_work_end_hour' => Schema::TYPE_INTEGER . '(10) DEFAULT NULL COMMENT \'结束的小时\'',
            'worker_work_date' => Schema::TYPE_DATE. ' DEFAULT NULL COMMENT \'工作安排日期\'',
            'worker_work_week_day' => Schema::TYPE_BOOLEAN . '(3) DEFAULT NULL COMMENT \'星期\'',
            'create_time' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'创建时间\'',
            'update_time' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'最后更新时间\'',
        ], $tableOptions);
    }

    public function down()
    {
        echo "m150918_153312_ejj_worker_work_time_table cannot be reverted.\n";

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
