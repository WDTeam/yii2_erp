<?php

use yii\db\Schema;
use yii\db\Migration;

class m150923_034334_create_table_shop extends Migration
{
    public function up()
    {
        $this->execute("
            CREATE TABLE IF NOT EXISTS {{%shop}} (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `name` varchar(100) DEFAULT NULL COMMENT '店名',
              `account` varchar(100) DEFAULT NULL COMMENT '账号',
              `password_hash` varchar(50) DEFAULT NULL COMMENT '密码HASH',
              `shop_manager_id` int(11) DEFAULT NULL DEFAULT '0' COMMENT '归属家政ID',
              `province_id` int(11) DEFAULT NULL COMMENT '省份ID',
              `city_id` int(11) DEFAULT NULL COMMENT '城市ID',
              `county_id` int(11) DEFAULT NULL COMMENT '区县ID',
              `street` varchar(255) DEFAULT NULL COMMENT '办公街道',
              `operation_shop_district_id` int(11) DEFAULT 0 COMMENT '商圈ID',
              `principal` varchar(50) DEFAULT NULL COMMENT '负责人',
              `tel` varchar(50) DEFAULT NULL COMMENT '电话',
              `other_contact` varchar(200) DEFAULT NULL COMMENT '其他联系方式',
              `bankcard_number` varchar(50) DEFAULT NULL COMMENT '银行卡号',
              `account_person` varchar(100) DEFAULT NULL COMMENT '开户人',
              `opening_bank` varchar(200) DEFAULT NULL COMMENT '开户行',
              `sub_branch` varchar(200) DEFAULT NULL COMMENT '支行名称',
              `opening_address` varchar(255) DEFAULT NULL COMMENT '开户地址',
              `created_at` int(11) DEFAULT '0' COMMENT '创建时间',
              `updated_at` int(11) DEFAULT '0' COMMENT '修改时间',
              `is_blacklist` int(3) DEFAULT '0' COMMENT '是否是黑名单：0正常，1黑名单',
              `audit_status` tinyint(1) DEFAULT '0' COMMENT '审核状态：0未审核，1通过，2不通过',
              `worker_count` int(11) DEFAULT '0' COMMENT '阿姨数量',
              `complain_coutn` int(11) DEFAULT '0' COMMENT '投诉数量',
              `level` varchar(50) DEFAULT NULL COMMENT '评级',
              `isdel` tinyint(1) DEFAULT NULL COMMENT '是否删除',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8
        ");
        
        $this->insert('{{%shop}}', [
            'id'=>1,
            'name'=>'E家洁自营门店',
            'shop_manager_id'=>1,
            'province_id'=>110000,
            'city_id'=>110100,
            'county_id'=>110105,
            'street'=>'光华SOHO 2单元708',
            'principal'=>'云涛',
            'tel'=>'123456',
            'audit_status'=>1
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%shop}}');
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
