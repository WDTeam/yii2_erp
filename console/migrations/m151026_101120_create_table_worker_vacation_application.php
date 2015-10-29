<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151026_101120_create_table_worker_vacation_application extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'阿姨请假申请表\'';
        }
        $this->createTable('{{%worker_vacation_application}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT COMMENT \'阿姨请假申请表自增id\'' ,
            'worker_id' => Schema::TYPE_INTEGER . '(10) DEFAULT NULL COMMENT \'阿姨id\'',
            'worker_vacation_application_start_time' => Schema::TYPE_INTEGER . '(10) DEFAULT NULL COMMENT \'阿姨申请请假开始时间\'',
            'worker_vacation_application_end_time' => Schema::TYPE_INTEGER . '(10) DEFAULT NULL COMMENT \'阿姨申请请假结束时间\'',
            'worker_vacation_application_type' => Schema::TYPE_INTEGER . '(10) DEFAULT NULL COMMENT \'阿姨申请请假类型 1休假 2事假\'',
            'worker_vacation_application_approve_status' => Schema::TYPE_INTEGER . '(10) DEFAULT 0 COMMENT \'阿姨申请审核状态 0待审核1审核通过2审核不通过\'',
            'worker_vacation_application_approve_time' => Schema::TYPE_INTEGER . '(10) DEFAULT NULL COMMENT \'审核操作时间\'',
            'created_ad' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'创建时间\'',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%worker_vacation_application}}');
    }
}
