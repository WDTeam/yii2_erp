<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_144324_ejj_worker_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'阿姨信息表\'';
        }
        $this->createTable('ejj_worker', [
            
              'id' => Schema::TYPE_PK . ' AUTO_INCREMENT COMMENT \'阿姨封号表自增id\'' ,
              'shop_id'  => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'门店id\'',
              'worker_name' => Schema::TYPE_STRING . '(10) DEFAULT NULL COMMENT \'阿姨姓名\'',
              'worker_phone' => Schema::TYPE_STRING . '(20) DEFAULT NULL COMMENT \'阿姨手机\'',
              'worker_idcard' => Schema::TYPE_STRING . '(20) DEFAULT NULL COMMENT \'阿姨身份证号\'',
              'worker_work_city'  => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'阿姨工作城市\'',
              'worker_work_area'  => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'阿姨工作区县\'',
              'worker_work_street' => Schema::TYPE_STRING . '(50) DEFAULT NULL COMMENT \'阿姨常用工作地址\'',
              'worker_work_lng' =>Schema::TYPE_FLOAT.' DEFAULT NULL COMMENT \'阿姨常用工作经度\'',
              'worker_work_lat' =>Schema::TYPE_FLOAT.' DEFAULT NULL COMMENT \'阿姨常用工作纬度\'',
              'worker_live_province'  => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'阿姨居住地(省份)\'',
              'worker_live_city'  => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'阿姨居住地(市)\'',
              'worker_live_area'  => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'阿姨居住地(区,县)\'',
              'worker_live_street' => Schema::TYPE_STRING . '(50) DEFAULT NULL COMMENT \'阿姨居住地(详细地址)\'',
              'worker_live_lng' =>Schema::TYPE_FLOAT.' DEFAULT 0 COMMENT \'阿姨居住地经度\'',
              'worker_live_lat' =>Schema::TYPE_FLOAT.' DEFAULT  0 COMMENT \'阿姨居住地纬度\'',
              'worker_approve_status' => Schema::TYPE_BOOLEAN  . '(1) NOT NULL DEFAULT  0 COMMENT \'阿姨审核状态 0未通过1通过\'',
              'worker_auth_status' => Schema::TYPE_BOOLEAN  . '(1) NOT NULL DEFAULT 0 COMMENT \'阿姨试工状态 0未试工，1已试工\'',
              'worker_onboard_status' => Schema::TYPE_BOOLEAN  . '(1) NOT NULL DEFAULT 0 COMMENT \'阿姨上岗状态 0未上岗 1已上岗 \'',
              'worker_photo' => Schema::TYPE_STRING . '(40)  DEFAULT \'\' COMMENT \'阿姨头像地址\'',
              'worker_level' => Schema::TYPE_BOOLEAN  . '(4)   DEFAULT 1 COMMENT \'阿姨等级\'',
              'worker_password' => Schema::TYPE_STRING . '(50) DEFAULT NULL COMMENT \'阿姨端登录密码\'',
              'worker_type' => Schema::TYPE_BOOLEAN  . '(1) NOT NULL DEFAULT 1 COMMENT \'阿姨类型 1自有 2非自有\'',
              'worker_identity' => Schema::TYPE_BOOLEAN  . '(3) NOT NULL  DEFAULT  1 COMMENT \'阿姨身份 1全职2兼职3时段4高峰\'',
              'worker_status' => Schema::TYPE_BOOLEAN  . '(3) NOT NULL  DEFAULT  0 COMMENT \'阿姨状态 0正常1封号\'',
              'create_time'  => Schema::TYPE_INTEGER . '(10)  DEFAULT NULL COMMENT \'阿姨录入时间\'',
              'update_time'  => Schema::TYPE_INTEGER . '(10)  DEFAULT NULL COMMENT \'最后更新时间\'',
              'is_del' => Schema::TYPE_BOOLEAN  . '(1) NOT NULL DEFAULT 0 COMMENT \'是否删号 0正常1删号\'',
        ], $tableOptions);
    }

    public function down()
    {
        echo "m150918_144324_ejj_worker_table cannot be reverted.\n";

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
