<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_031530_create_table_city extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%city}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'编号\'' ,
            'city_name' => Schema::TYPE_STRING . '(30) NOT NULL COMMENT \'城市名称\'',
            'city_is_online' => Schema::TYPE_SMALLINT . '(1) NOT NULL COMMENT \'城市是否上线\'',
            'create_time' => Schema::TYPE_INTEGER. '(11) NOT NULL COMMENT \'创建时间\'',
            'updatetime' => Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT \'编辑时间\'',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%city}}');
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
