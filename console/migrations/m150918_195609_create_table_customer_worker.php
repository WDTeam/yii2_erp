<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_195609_create_table_customer_worker extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'用户阿姨表\'';
        }
        $this->createTable('{{%customer_worker}}', [
            'id'=>  Schema::TYPE_PK.'(8) NOT NULL AUTO_INCREMENT COMMENT \'主键\'' ,
            'customer_id' => Schema::TYPE_INTEGER.'(8) NOT NULL COMMENT \'关联用户\'',
            'woker_id'=>  Schema::TYPE_INTEGER.'(8) NOT NULL COMMENT \'关联阿姨\'',
            'customer_worker_status'=>  Schema::TYPE_SMALLINT.'(4) NOT NULL COMMENT \'阿姨类型1为默认阿姨，-1为非默认阿姨\'' ,
            'created_at'=>  Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'创建时间\'',
            'updated_at'=>  Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'更新时间\'',
            'is_del'=> Schema::TYPE_SMALLINT.'(4) DEFAULT 0 COMMENT \'是否逻辑删除\'',
            ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%customer_worker}}');
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
