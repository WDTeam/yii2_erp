<?php

use yii\db\Schema;
use yii\db\Migration;

class m150926_035424_create_table_operation_platform_version extends Migration
{
    const TB_NAME = '{{%operation_platform_version}}';

    public function up()
    {
        $sql = 'DROP TABLE IF EXISTS ' . self::TB_NAME;
        $this->execute($sql);

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'广告平台版本表\'';
        }
        $this->createTable(self::TB_NAME, [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT COMMENT \'编号\'' ,
            'operation_platform_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'平台编号\'',
            'operation_platform_name' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'平台名称\'',
            'operation_platform_version_name' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'版本名称\'',
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
