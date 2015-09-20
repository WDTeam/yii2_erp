<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_113455_create_table_system_admin extends Migration
{
    public function up()
    { 
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT=\'管理员列表\'';
        }
        $this->createTable('{{%system_admin}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'自增id（管理员ID）\'' ,
            'system_admin_status' => Schema::TYPE_SMALLINT . '(2) NOT NULL DEFAULT \'1\' COMMENT \'管理员当前状态，1表示正常，-1表示异常\'',
            'system_admin_email' => Schema::TYPE_STRING . '(50) DEFAULT NULL COMMENT \'管理员邮箱\'',
            'system_admin_pwd' => Schema::TYPE_STRING . '(32) DEFAULT NULL COMMENT \'管理员密码\'',
            'system_admin_real_name' => Schema::TYPE_STRING. '(10) DEFAULT NULL COMMENT \'管理员真实姓名\'',
            'system_admin_phone' => Schema::TYPE_BIGINT . '(20) DEFAULT 5 COMMENT \'管理员电话\'',
            'system_admin_photo' => Schema::TYPE_STRING. '(255) COMMENT \'管理员头像\'',
            'system_admin_login_time' => Schema::TYPE_INTEGER. '(11) COMMENT \'管理员登录时间\'',
            'system_admin_login_ip' => Schema::TYPE_STRING. '(20) DEFAULT NULL COMMENT \'管理员登录的ip地址\'',
            'system_admin_register_time' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'管理员注册的时间\'',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%system_admin}}');
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
