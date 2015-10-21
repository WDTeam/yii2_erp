<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151021_040747_create_table_worker_auth extends Migration
{
    public function Up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'阿姨审核表\'';
        }
        $this->createTable('{{%worker_auth}}', [
            'worker_id' => Schema::TYPE_PK . '(11)  COMMENT \'主表阿姨id\'',
            'worker_auth_status' => Schema::TYPE_BOOLEAN . '(4) DEFAULT 0 COMMENT \'阿姨审核状态(0未审核,1审核通过2审核不通过)\'',
            'worker_auth_failed_reason' => Schema::TYPE_STRING  . '(255) DEFAULT NULL COMMENT \'审核不通过原因\'',
            'worker_basic_training_status' => Schema::TYPE_BOOLEAN. '(4) DEFAULT 0 COMMENT \'阿姨基础培训(0培训中1培训通过2培训不通过)\'',
            'worker_ontrial_status' => Schema::TYPE_BOOLEAN . '(4) DEFAULT 0 COMMENT \'阿姨试工状态(0未试工1试工通过2试工不通过)\'',
            'worker_ontrial_failed_reason' => Schema::TYPE_STRING . '(255) DEFAULT NULL COMMENT \'试工不通过原因\'',
            'worker_onboard_status' => Schema::TYPE_BOOLEAN . '(4) DEFAULT 0 COMMENT \'上岗状态(0未上岗1上岗通过2上岗不通过)\'',
            'worker_onboard_failed_reason' => Schema::TYPE_STRING . '(255) DEFAULT NULL COMMENT \'上岗不通过原因\'',
            'worker_upgrade_training_status' => Schema::TYPE_BOOLEAN . '(4) DEFAULT 0 COMMENT \'阿姨晋升培训状态(0未培训1培训通过2培训不通过)\'',
        ], $tableOptions);
    }

    public function Down()
    {
        $this->dropTable('{{%worker_auth}}');

        return true;
    }
}
