<?php

use yii\db\Schema;
use yii\db\Migration;

class m150926_035618_create_table_operation_advert_content extends Migration
{
    const TB_NAME = '{{%operation_advert_content}}';

    public function up()
    {
        $sql = 'DROP TABLE IF EXISTS ' . self::TB_NAME;
        $this->execute($sql);

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'广告内容表\'';
        }
        $this->createTable(self::TB_NAME, [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT COMMENT \'编号\'' ,
            'operation_advert_content_name' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'广告名称\'',
            'position_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'位置编号\'',
            'position_name' => Schema::TYPE_STRING . '(50) DEFAULT NULL COMMENT \'位置名称\'',
            'platform_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'平台编号\'',
            'platform_name' => Schema::TYPE_STRING . '(50) DEFAULT NULL COMMENT \'平台名称\'',
            'platform_version_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'版本编号\'',
            'platform_version_name' => Schema::TYPE_STRING . '(50) DEFAULT NULL COMMENT \'版本名称\'',
            'operation_advert_start_time' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'活动开始时间\'',
            'operation_advert_end_time' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'活动结束时间\'',
            'operation_advert_online_time' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'广告上线时间\'',
            'operation_advert_offline_time' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'广告下线时间\'',
            'operation_advert_picture_text' => Schema::TYPE_STRING. '(255) DEFAULT NULL COMMENT \'广告图片或广告文字\'',
            'operation_advert_url' => Schema::TYPE_STRING. '(255) DEFAULT NULL COMMENT \'广告链接地址\'',
            'is_softdel' => Schema::TYPE_SMALLINT . '(1) unsigned NOT NULL DEFAULT 0 COMMENT \'状态\'',
            'created_at' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'编辑时间\'',
            'operation_advert_content_orders' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'排序\'',
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
