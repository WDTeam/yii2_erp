<?php

use yii\db\Schema;
use yii\db\Migration;

class m150919_092739_create_table_worker extends Migration
{
    public function up()
    {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'阿姨信息表\'';
        }
        $this->createTable('{{%worker}}', [

            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT COMMENT \'阿姨表自增id\'' ,
            'shop_id'  => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'门店id\'',
            'worker_name' => Schema::TYPE_STRING . '(10) DEFAULT NULL COMMENT \'阿姨姓名\'',
            'worker_phone' => Schema::TYPE_STRING . '(20) DEFAULT NULL COMMENT \'阿姨手机\'',
            'worker_idcard' => Schema::TYPE_STRING . '(20) DEFAULT NULL COMMENT \'阿姨身份证号\'',
            'worker_password' => Schema::TYPE_STRING . '(50) DEFAULT NULL COMMENT \'阿姨端登录密码\'',
            'worker_photo' => Schema::TYPE_STRING . '(150)  DEFAULT \'\' COMMENT \'阿姨头像地址\'',
            'worker_level' => Schema::TYPE_BOOLEAN  . '(4)   DEFAULT 1 COMMENT \'阿姨等级\'',
            'worker_auth_status' => Schema::TYPE_BOOLEAN  . '(1) NOT NULL DEFAULT  0 COMMENT \'阿姨审核状态 0新录入1已审核2已基础培训3已试工4已上岗5已晋升培训\'',
//            'worker_auth_status' => Schema::TYPE_BOOLEAN  . '(1) NOT NULL DEFAULT  0 COMMENT \'阿姨审核状态 0未通过1通过\'',
//            'worker_ontrial_status' => Schema::TYPE_BOOLEAN  . '(1) NOT NULL DEFAULT 0 COMMENT \'阿姨试工状态 0未试工，1已试工\'',
//            'worker_onboard_status' => Schema::TYPE_BOOLEAN  . '(1) NOT NULL DEFAULT 0 COMMENT \'阿姨上岗状态 0未上岗 1已上岗 \'',
            'worker_work_city'  => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'阿姨工作城市\'',
            'worker_work_area'  => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'阿姨工作区县\'',
            'worker_work_street' => Schema::TYPE_STRING . '(50) DEFAULT NULL COMMENT \'阿姨常用工作地址\'',
            'worker_work_lng' =>Schema::TYPE_FLOAT.' DEFAULT NULL COMMENT \'阿姨常用工作经度\'',
            'worker_work_lat' =>Schema::TYPE_FLOAT.' DEFAULT NULL COMMENT \'阿姨常用工作纬度\'',
            'worker_star' => Schema::TYPE_BOOLEAN . '(4) NOT NULL  DEFAULT  3 COMMENT \'阿姨星级\'',
            'worker_type' => Schema::TYPE_BOOLEAN  . '(1) NOT NULL DEFAULT 1 COMMENT \'阿姨类型 1自有 2非自有\'',
            'worker_rule_id' => Schema::TYPE_BOOLEAN  . '(3) NOT NULL  DEFAULT  1 COMMENT \'阿姨角色id \'',
            'worker_identity_id' => Schema::TYPE_BOOLEAN  . '(3) NOT NULL  DEFAULT  1 COMMENT \'阿姨身份id \'',
            'worker_is_block' => Schema::TYPE_BOOLEAN  . '(3) NOT NULL  DEFAULT  0 COMMENT \'阿姨是否封号 0正常1封号\'',
            'worker_is_vacation' => Schema::TYPE_BOOLEAN  . '(3) NOT NULL  DEFAULT  0 COMMENT \'阿姨是否请假 0正常1请事假中2请休假中\'',
            'worker_is_blacklist' => Schema::TYPE_BOOLEAN  . '(3) NOT NULL  DEFAULT  0 COMMENT \'阿姨是否黑名单 0正常1黑名单\'',
            'worker_blacklist_reason' => Schema::TYPE_STRING  . '(255) DEFAULT  NULL COMMENT \'阿姨被加入黑名单的原因\'',
            'worker_blacklist_time' => Schema::TYPE_INTEGER . '(10) NOT NULL  DEFAULT  0 COMMENT \'阿姨加入黑名单的原因\'',
            'worker_is_dimission' => Schema::TYPE_BOOLEAN  . '(3) NOT NULL  DEFAULT  0 COMMENT \'阿姨是否离职\'',
            'worker_dimission_reason' => Schema::TYPE_STRING  . '(255) DEFAULT  NULL COMMENT \'阿姨离职原因\'',
            'worker_dimission_time' => Schema::TYPE_INTEGER . '(10) NOT NULL  DEFAULT  0 COMMENT \'阿姨离职时间\'',
            'created_ad'  => Schema::TYPE_INTEGER . '(10)  DEFAULT NULL COMMENT \'阿姨录入时间\'',
            'updated_ad'  => Schema::TYPE_INTEGER . '(10)  DEFAULT NULL COMMENT \'最后更新时间\'',
            'isdel' => Schema::TYPE_BOOLEAN  . '(1) NOT NULL DEFAULT 0 COMMENT \'是否删号 0正常1删号\'',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%worker}}');

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
