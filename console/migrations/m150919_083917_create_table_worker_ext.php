<?php

use yii\db\Schema;
use yii\db\Migration;

class m150919_083917_create_table_worker_ext extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'阿姨基本信息表\'';
        }
        $this->createTable('{{%worker_ext}}', [
            'worker_id' => Schema::TYPE_PK . '(11)  COMMENT \'阿姨id\'',
            'worker_age' => Schema::TYPE_BOOLEAN . '(3) DEFAULT NULL COMMENT \'阿姨年龄\'',
            'worker_sex' => Schema::TYPE_BOOLEAN . '(1) DEFAULT NULL COMMENT \'阿姨性别\'',
            //'worker_birth' => Schema::TYPE_DATE. '(1) DEFAULT NULL COMMENT \'阿姨生日\'',
            'worker_edu' => Schema::TYPE_STRING . '(30) DEFAULT NULL COMMENT \'阿姨教育程度\'',
            'worker_is_health' => Schema::TYPE_BOOLEAN . '(1) DEFAULT 0 COMMENT \'阿姨是否有健康证 0没有，1有\'',
            'worker_is_insurance' => Schema::TYPE_BOOLEAN . '(1) DEFAULT 0 COMMENT \'阿姨是否上保险 0否，1是\'',
            'worker_height' => Schema::TYPE_SMALLINT. '(4) DEFAULT NULL COMMENT \'阿姨身高(cm)\'',
            'worker_source' => Schema::TYPE_STRING . '(50) DEFAULT NULL COMMENT \'阿姨来源\'',
            'worker_hometown' => Schema::TYPE_STRING . '(50) DEFAULT NULL COMMENT \'阿姨籍贯\'',
            'worker_bank_name' => Schema::TYPE_STRING . '(10) DEFAULT NULL COMMENT \'开户银行\'',
            'worker_bank_from' => Schema::TYPE_STRING . '(50) DEFAULT NULL COMMENT \'银行卡开户网点\'',
            'worker_bank_area' => Schema::TYPE_STRING . '(50) DEFAULT NULL COMMENT \'银行开户地\'',
            'worker_bank_card' => Schema::TYPE_STRING . '(30) DEFAULT NULL COMMENT \'银行卡号\'',
            'worker_live_province'  => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'阿姨居住地(省份)\'',
            'worker_live_city'  => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'阿姨居住地(市)\'',
            'worker_live_area'  => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'阿姨居住地(区,县)\'',
            'worker_live_street' => Schema::TYPE_STRING . '(50) DEFAULT NULL COMMENT \'阿姨居住地(详细地址)\'',
            'worker_live_lng' =>Schema::TYPE_FLOAT.' DEFAULT 0 COMMENT \'阿姨居住地经度\'',
            'worker_live_lat' =>Schema::TYPE_FLOAT.' DEFAULT  0 COMMENT \'阿姨居住地纬度\'',
            'created_ad' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'创建时间\'',
            'updated_ad' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'最后更新时间\'',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%worker_ext}}');

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
