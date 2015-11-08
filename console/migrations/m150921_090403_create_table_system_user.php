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
              `auth_key` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
              `password_hash` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '加密密文',
              `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'token',
              `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '邮箱',
              `mobile` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '手机号',
              `classify` int(2)  DEFAULT 0 COMMENT '用户分类,0:系统保留，1管理员，2minibox',
              `status` smallint(6) DEFAULT NULL DEFAULT '10' COMMENT '状态',
              `created_at` int(11) DEFAULT NULL COMMENT '创建时间',
              `updated_at` int(11) DEFAULT NULL COMMENT '修改时间',
              PRIMARY KEY (`id`),
              UNIQUE KEY `username` (`username`),
              KEY `status` (`status`),
              KEY `created_at` (`created_at`)
            ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='后台用户表'
       ")->execute();
        /**
         * 插入默认用户
         */
        $this->insert('{{%system_user}}', [
            'id'=>1,
            'username'=>'system',
            'status'=>1
        ]);
        $this->insert('{{%system_user}}', [
            'id'=>2,
            'username'=>'customer',
            'password_hash'=>'$2y$13$H2h2XPss7i.FPQ3lCHamQu/qjqx8jEEFXwTR3vXdxpxQY.SpKBFSS1',
            'status'=>1
        ]);
        $this->insert('{{%system_user}}', [
            'id'=>3,
            'username'=>'worker',
            'password_hash'=>'$2y$13$H2h2XPss7i.FPQ3lCHamQu/qjqx8jEEFXwTR3vXdxpxQY.SpKBFSS1',
            'status'=>1
        ]);
        $this->insert('{{%system_user}}', [
            'id'=>4,
            'username'=>'admin',
            'password_hash'=>'$2y$13$H2h2XPss7i.FPQ3lCHamQu/qjqx8jEEFXwTR3vXdxpxQY.SpKBFSS',
            'classify'=>1,
            'status'=>1
        ]);
        $this->insert('{{%system_user}}', [
            'id'=>5,
            'username'=>'minibox_test',
            'password_hash'=>'$2y$13$H2h2XPss7i.FPQ3lCHamQu/qjqx8jEEFXwTR3vXdxpxQY.SpKBFSS',
            'classify'=>2,
            'status'=>1
        ]);
        $this->insert('{{%system_user}}', [
            'id'=>999,
            'username'=>'colee',
            'email'=>'lidenggao@1jiajie.com',
            'password_hash'=>'$2y$13$H2h2XPss7i.FPQ3lCHamQu/qjqx8jEEFXwTR3vXdxpxQY.SpKBFSS',
            'classify'=>2,
            'status'=>1
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%system_user}}');

        return true;
    }
}
