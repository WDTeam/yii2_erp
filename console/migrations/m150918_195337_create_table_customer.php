<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_195337_create_table_customer extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'客户表\'';
        }
        $this->createTable('{{%customer}}', [
            'id'=>  Schema::TYPE_PK.'(8) NOT NULL AUTO_INCREMENT COMMENT \'主键\'',
            'customer_name'=>  Schema::TYPE_STRING.'(16) NOT NULL COMMENT \'客户名称\'',
            'customer_sex' => Schema::TYPE_SMALLINT.'(4) NOT NULL COMMENT \'客户性别\'',
            'customer_birth'=>  Schema::TYPE_INTEGER.'(11) NULL DEFAULT NULL COMMENT \'客户生日\'',
            'customer_photo'=>  Schema::TYPE_STRING.'(32) DEFAULT NULL COMMENT \'客户头像\'',
            'customer_phone' => Schema::TYPE_STRING.'(11) NOT NULL COMMENT \'客户手机号\'',
            'customer_email'=>  Schema::TYPE_STRING.'(32) DEFAULT NULL COMMENT \'客户邮箱\'',
            'region_id'=>  Schema::TYPE_INTEGER.'(8) DEFAULT NULL COMMENT \'居住区域关联\'',
            'customer_live_address_detail'=>  Schema::TYPE_STRING.'(64) COMMENT \'客户详细地址\'',
            'customer_score'=>  Schema::TYPE_INTEGER.'(8) NOT NULL COMMENT \'用户积分\'',
            'customer_level'=>  Schema::TYPE_SMALLINT.'(4) NULL DEFAULT NULL COMMENT \'客户会员级别\'',
            'customer_src'=>  Schema::TYPE_SMALLINT.'(4) NULL DEFAULT NULL COMMENT \'客户来源，1为线下，2为线上\'',
            'channal_id'=>  Schema::TYPE_INTEGER.'(8) NULL DEFAULT NULL COMMENT \'关联渠道\'',
            'platform_id'=>  Schema::TYPE_INTEGER.'(8) NULL DEFAULT NULL COMMENT \'关联平台\'',
            'customer_login_ip'=> Schema::TYPE_STRING.'(16) DEFAULT NULL COMMENT \'登陆ip\'',
            'customer_login_time'=>  Schema::TYPE_INTEGER.'(11) DEFAULT NULL COMMENT \'登陆时间\'',
            'created_at'=>  Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'创建时间\'',
            'updated_at'=>  Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'更新时间\'',
            'is_del'=>  Schema::TYPE_SMALLINT.'(4) DEFAULT NULL COMMENT \'是否逻辑删除\'',
            ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%customer}}');
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
