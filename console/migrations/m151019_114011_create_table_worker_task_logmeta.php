<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151019_114011_create_table_worker_task_logmeta extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'阿姨任务记录条件数值表\'';
        }
        $this->createTable('{{%worker_task_logmeta}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'编号\'' ,
            'worker_task_id'=>Schema::TYPE_INTEGER . '(11) DEFAULT 0 COMMENT \'任务ID\'',
            'worker_tasklog_id'=>Schema::TYPE_INTEGER . '(11) DEFAULT 0 COMMENT \'任务记录ID\'',
            'worker_id'=>Schema::TYPE_INTEGER . '(11) DEFAULT 0 COMMENT \'阿姨ID\'',
            'worker_tasklog_condition' => Schema::TYPE_SMALLINT . '(3) DEFAULT NULL COMMENT \'条件索引\'',
            'worker_tasklog_value' => Schema::TYPE_INTEGER . '(11) DEFAULT 0 COMMENT \'条件值\'',
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%worker_task_logmeta}}');
    }
}
