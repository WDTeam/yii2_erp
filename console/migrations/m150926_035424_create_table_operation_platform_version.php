<?php

use yii\db\Schema;
use yii\db\Migration;

class m150926_035424_create_table_operation_platform_version extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'广告平台版本表\'';
        }
        $this->createTable('{{%operation_platform_version}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT COMMENT \'编号\'' ,
            'operation_platform_ids' => Schema::TYPE_TEXT . ' DEFAULT NULL COMMENT \'平台编号\'',
            'operation_platform_names' => Schema::TYPE_TEXT . ' DEFAULT NULL COMMENT \'平台名称\'',
            'operation_platform_version_name' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'版本名称\'',
            'created_at' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'编辑时间\'',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%operation_platform_version}}');

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
