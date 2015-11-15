<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_031530_create_table_operation_city extends Migration
{
    const TB_NAME = '{{%operation_city}}';

    public function up()
    {
        $sql = 'DROP TABLE IF EXISTS ' . self::TB_NAME;
        $this->execute($sql);
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'开通城市表\'';
        }
        $this->createTable(self::TB_NAME, [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'编号\'' ,
            'province_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'省份编号\'',
            'province_name' => Schema::TYPE_STRING . '(30) DEFAULT NULL COMMENT \'省份名称\'',
            'city_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'城市编号\'',
            'city_name' => Schema::TYPE_STRING . '(50) DEFAULT NULL COMMENT \'城市名称\'',
            'operation_city_is_online' => Schema::TYPE_SMALLINT . '(1) DEFAULT 2 COMMENT \'城市是否上线（1为上线，2为下线）\'',
            'is_softdel' => Schema::TYPE_SMALLINT . '(1) unsigned NOT NULL DEFAULT 0 COMMENT \'状态\'',
            'created_at' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'编辑时间\'',
        ], $tableOptions);
        
    }

    public function down()
    {
        $this->dropTable(self::TB_NAME);

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
