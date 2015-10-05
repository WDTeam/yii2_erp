<?php

use yii\db\Schema;
use yii\db\Migration;

class m150926_035719_create_table_operation_advert_release extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'广告发布表\'';
        }
        $this->createTable('{{%operation_advert_release}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT COMMENT \'编号\'' ,
            'operation_city_id' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'城市编号\'',
            'operation_city_name' => Schema::TYPE_TEXT . ' DEFAULT NULL COMMENT \'城市名称\'',
            'operation_platform_id' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'平台编号\'',
            'operation_platform_name' => Schema::TYPE_TEXT . ' DEFAULT NULL COMMENT \'平台名称\'',
            'operation_platform_version_id' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'版本编号\'',
            'operation_platform_version_name' => Schema::TYPE_TEXT . ' DEFAULT NULL COMMENT \'版本名称\'',
            'operation_advert_position_id' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'广告位置编号\'',
            'operation_advert_position_name' => Schema::TYPE_TEXT . ' DEFAULT NULL COMMENT \'广告位置名称\'',
            'operation_advert_content_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'广告内容编号\'',
            'operation_advert_content_name' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'广告内容名称\'',
            'created_at' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'编辑时间\'',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%operation_advert_release}}');

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
