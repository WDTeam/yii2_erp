<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_132800_ejj_user extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'客户表\'';
        }
        $this->createTable('{{%ejj_user}}', [
            'id'=>  Schema::TYPE_PK.'(8) NOT NULL AUTO_INCREMENT COMMENT \'主键\'' ,
            'user_name'=>  Schema::STRING.'(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT \'客户名称\'' ,
            'user_sex' => Schema::TYPE_SMALLINT.'(4) NOT NULL' ,
            'user_birth'=>  Schema::TYPE_INTEGER.'(11) NULL DEFAULT NULL COMMENT \'客户生日\'' ,
            'user_photo'=>  Schema::STRING.'(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT \'客户头像\' ',
            'user_phone' => Schema::STRING.'(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT \'客户手机号\'' ,
            'user_email'=>  Schema::STRING.'(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT \'客户邮箱\' ',
            'region_id'=>  Schema::TYPE_INTEGER.'(8) NULL DEFAULT NULL COMMENT \'居住区域关联\'' ,
            'user_live_address_detail'=>  Schema::STRING.'(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL' ,
            'user_score'=>  Schema::TYPE_INTEGER.'(8) NOT NULL COMMENT \'用户积分\'' ,
            'user_level'=>  Schema::TYPE_SMALLINT.'(4) NULL DEFAULT NULL COMMENT \'客户会员级别\'' ,
            'user_src'=>  Schema::TYPE_SMALLINT.'(4) NULL DEFAULT NULL COMMENT \'客户来源，1为线下，2为线上\'' ,
            'channal_id'=>  Schema::TYPE_INTEGER.'(8) NULL DEFAULT NULL COMMENT \'关联渠道\'' ,
            'platform_id'=>  Schema::TYPE_INTEGER.'(8) NULL DEFAULT NULL COMMENT \'关联平台\'' ,
            'user_login_ip'=> Schema::STRING.'(16) DEFAULT NULL' ,
            'user_login_time'=>  Schema::TYPE_INTEGER.'(11) NULL DEFAULT NULL' ,
            'created_at'=>  Schema::TYPE_INTEGER.'(11) NOT NULL' ,
            'updated_at'=>  Schema::TYPE_INTEGER.'(11) NOT NULL' ,
            'is_del'=>  Schema::TYPE_SMALLINT.'(4) NULL DEFAULT NULL' ,
            ]);
    }

    public function down()
    {
        $this->dropTable('{{%ejj_user}}');
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
