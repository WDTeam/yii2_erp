<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_151816_ejj_worker_info_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'阿姨基本信息表\'';
        }
        $this->createTable('ejj_worker_info', [
            'worker_id' => Schema::TYPE_PK . '(11)  COMMENT \'阿姨id\'',
            'worker_info_age' => Schema::TYPE_BOOLEAN . '(3) DEFAULT NULL COMMENT \'阿姨年龄\'',
            'worker_info_sex' => Schema::TYPE_BOOLEAN . '(1) DEFAULT NULL COMMENT \'阿姨性别\'',
            'worker_info_birth' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'阿姨生日\'',
            'worker_info_edu' => Schema::TYPE_STRING . '(30) DEFAULT NULL COMMENT \'阿姨教育程度\'',
            'worker_info_hometown' => Schema::TYPE_STRING . '(50) DEFAULT NULL COMMENT \'阿姨籍贯\'',
            'worker_info_is_health' => Schema::TYPE_BOOLEAN . '(1) DEFAULT 0 COMMENT \'阿姨是否有健康证 0没有，1有\'',
            'worker_info_is_insurance' => Schema::TYPE_BOOLEAN . '(1) DEFAULT 0 COMMENT \'阿姨是否上保险 0否，1是\'',
            'worker_info_source' => Schema::TYPE_STRING . '(50) DEFAULT NULL COMMENT \'阿姨来源\'',
            'worker_info_bank_name' => Schema::TYPE_STRING . '(10) DEFAULT NULL COMMENT \'开户银行\'',
            'worker_info_bank_from' => Schema::TYPE_STRING . '(40) DEFAULT NULL COMMENT \'银行卡开户网点\'',
            'worker_info_bank_card' => Schema::TYPE_STRING . '(30) DEFAULT NULL COMMENT \'银行卡号\'',
            'create_time' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'创建时间\'',
            'update_time' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'最后更新时间\'',
        ], $tableOptions);
    }

    public function down()
    {
        echo "m150918_151816_ejj_worker_info_table cannot be reverted.\n";

        return false;
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
