<?php

use yii\db\Schema;
use yii\db\Migration;

class m150921_090403_create_table_system_user extends Migration
{
    public function up()
    {
        \Yii::$app->db->createCommand("
            CREATE TABLE {{%system_user}} (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户名',
              `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
              `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '加密密文',
              `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'token',
              `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '邮箱',
              `role` varchar(64) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '角色',
              `status` smallint(6) NOT NULL DEFAULT '10' COMMENT '状态',
              `created_at` int(11) NOT NULL COMMENT '创建时间',
              `updated_at` int(11) NOT NULL COMMENT '修改时间',
              PRIMARY KEY (`id`),
              UNIQUE KEY `username` (`username`),
              KEY `role` (`role`),
              KEY `status` (`status`),
              KEY `created_at` (`created_at`)
            ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='后台用户表'
       ")->execute();
        \Yii::$app->db->createCommand("
            INSERT INTO {{%system_user}} VALUES (1,'admin','1epI5YqrEp69yYopnIupWzaIbpbG45-M','\$2y\$13\$H2h2XPss7i.FPQ3lCHamQu/qjqx8jEEFXwTR3vXdxpxQY.SpKBFSS','','admin@demo.com','',1,1438409505,1438409505);
        ")->execute();
    }

    public function down()
    {
        $this->dropTable('{{%system_user}}');

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
