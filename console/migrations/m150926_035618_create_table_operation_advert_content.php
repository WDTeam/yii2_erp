<?php

use yii\db\Schema;
use yii\db\Migration;

class m150926_035618_create_table_operation_advert_content extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'广告内容表\'';
        }
        $this->createTable('{{%operation_advert_content}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT COMMENT \'编号\'' ,
            'operation_advert_position_name' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'广告名称\'',
            'operation_city_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'城市编号\'',
            'operation_city_name' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'城市名称\'',
            'operation_advert_start_time' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'活动开始时间\'',
            'operation_advert_end_time' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'活动结束时间\'',
            'operation_advert_online_time' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'广告上线时间\'',
            'operation_advert_offline_time' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'广告下线时间\'',
            'operation_advert_picture' => Schema::TYPE_STRING. '(255) DEFAULT NULL COMMENT \'广告图片\'',
            'operation_advert_url' => Schema::TYPE_STRING. '(255) DEFAULT NULL COMMENT \'广告链接地址\'',
            'created_at' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'编辑时间\'',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%operation_advert_content}}');
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
