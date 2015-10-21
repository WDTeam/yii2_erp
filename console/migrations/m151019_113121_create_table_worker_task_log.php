<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151019_113121_create_table_worker_task_log extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'阿姨任务记录表\'';
        }
        $this->createTable('{{%worker_task_log}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'编号\'' ,
            'worker_id'=>Schema::TYPE_INTEGER . '(11) DEFAULT 0 COMMENT \'阿姨ID\'',
            'worker_task_id'=>Schema::TYPE_INTEGER . '(11) DEFAULT 0 COMMENT \'任务ID\'',
            'worker_task_name' => Schema::TYPE_STRING . '(255) DEFAULT NULL COMMENT \'任务名称\'',
            'worker_task_start' => Schema::TYPE_INTEGER . '(11) DEFAULT 0 COMMENT \'任务开始时间\'',
            'worker_task_end' => Schema::TYPE_INTEGER . '(11) DEFAULT 0 COMMENT \'任务结束时间\'',
            'worker_task_is_done' => Schema::TYPE_INTEGER . '(11) DEFAULT 0 COMMENT \'任务结束时间\'',
            'worker_task_done_time' => Schema::TYPE_INTEGER . '(11) DEFAULT 0 COMMENT \'任务完成时间\'',
            'worker_task_reward_type'=>Schema::TYPE_SMALLINT . '(3) DEFAULT 0 COMMENT \'任务奖励类型\'',
            'worker_task_reward_value'=>Schema::TYPE_INTEGER . '(8) DEFAULT 0 COMMENT \'任务奖励值\'',
            
            'created_at'=>  Schema::TYPE_INTEGER.'(11) DEFAULT 0 COMMENT \'创建时间\'' ,
            'updated_at'=>  Schema::TYPE_INTEGER.'(11) DEFAULT 0 COMMENT \'更新时间\'' ,
            'is_del'=>  Schema::TYPE_BOOLEAN.' DEFAULT 0 COMMENT \'是否逻辑删除\'' ,
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%worker_task_log}}');
    }
}
