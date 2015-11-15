<?php

use yii\db\Schema;
use yii\db\Migration;

class m150923_052037_create_table_operation_area extends Migration
{
    const TB_NAME = '{{%operation_area}}';

    public function up()
    {
        $sql = 'DROP TABLE IF EXISTS ' . self::TB_NAME;
        $this->execute($sql);

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'行政区域表（省市县乡镇）\'';
        }
        $this->createTable(self::TB_NAME, [
            'id' => Schema::TYPE_PK . '  COMMENT \'编号\'' ,
            'area_name' => Schema::TYPE_STRING . '(100) DEFAULT NULL COMMENT \'区域名称\'',
            'parent_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'父名称\'',
            'short_name' => Schema::TYPE_STRING . '(50) DEFAULT NULL COMMENT \'简称\'',
            'longitude' => Schema::TYPE_STRING. '(30) DEFAULT NULL COMMENT \'经度\'',
            'latitude' => Schema::TYPE_STRING . '(30) DEFAULT NULL COMMENT \'纬度\'',
            'level' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'行政级别：1省（直辖市），2地级市（地区），3县,区，县级市 ，4：乡镇街道\'',
            'position' => Schema::TYPE_STRING . '(100) DEFAULT NULL COMMENT \'逻辑关系位置\'',
            'sort' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'排序\'',
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
