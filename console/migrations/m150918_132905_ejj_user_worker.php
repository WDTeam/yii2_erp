<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_132905_ejj_user_worker extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'用户阿姨表\'';
        }
        $this->createTable('{{%ejj_user_worker}}', [
            'id'=>  Schema::TYPE_PK.'(8) NOT NULL AUTO_INCREMENT COMMENT \'主键\'' ,
            'user_id' => Schema::TYPE_INTEGER.'(8) NOT NULL COMMENT \'关联用户\'' ,
            'woker_id'=>  Schema::TYPE_INTEGER.'(8) NOT NULL COMMENT \'关联阿姨\'' ,
            'user_worker_state'=>  Schema::TYPE_SMALLINT.'(4) NOT NULL COMMENT \'1为默认阿姨，-1为非默认阿姨\'' ,
            'created_at'=>  Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'创建时间\'' ,
            'updated_at'=>  Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'更新时间\'' ,
            'is_del'=> Schema::TYPE_SMALLINT.'(4) NULL DEFAULT NULL' ,
            ]);
    }

    public function down()
    {
        $this->dropTable('{{%ejj_user_worker}}');
    }
}
