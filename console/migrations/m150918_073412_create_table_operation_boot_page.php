<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_073412_create_table_operation_boot_page extends Migration
{
    public function up(){
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'启动页表\'';
        }
        $this->createTable('{{%operation_boot_page}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'编号\'' ,
            'operation_boot_page_name' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'启动页名称\'',
            'operation_boot_page_ios_img' => Schema::TYPE_STRING . '(255) DEFAULT NULL COMMENT \'ios图片\'',
            'operation_boot_page_android_img' => Schema::TYPE_STRING . '(255) DEFAULT NULL COMMENT \'android图片\'',
            'operation_boot_page_url' => Schema::TYPE_STRING. '(255) DEFAULT NULL COMMENT \'启动页连接地址\'',
            'operation_boot_page_residence_time' => Schema::TYPE_INTEGER . '(11) DEFAULT 5 COMMENT \'停留时间 单位为：秒 (默认5秒)\'',
            'operation_boot_page_online_time' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'上线时间 （时间戳）\'',
            'operation_boot_page_offline_time' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'下线时间 （时间戳）\'',
            'is_softdel' => Schema::TYPE_SMALLINT . '(1) unsigned NOT NULL DEFAULT 1 COMMENT \'状态\'',
            'created_at' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'编辑时间\'',
        ], $tableOptions);
    }

    public function down(){
        $this->dropTable('{{%operation_boot_page}}');
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
