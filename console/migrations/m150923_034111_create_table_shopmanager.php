<?php

use yii\db\Schema;
use yii\db\Migration;

class m150923_034111_create_table_shopmanager extends Migration
{
    public function up()
    {
        $this->execute("
            CREATE TABLE IF NOT EXISTS {{%shop_manager}} (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `name` varchar(255) DEFAULT NULL COMMENT '家政名称',
              `account` varchar(100) DEFAULT NULL COMMENT '账号',
              `password_hash` varchar(50) DEFAULT NULL COMMENT '密码HASH',
              `province_id` int(11) DEFAULT NULL COMMENT '省份ID',
              `city_id` int(11) DEFAULT NULL COMMENT '城市ID',
              `county_id` int(11) DEFAULT NULL COMMENT '区县ID',
              `street` varchar(255) DEFAULT NULL COMMENT '办公街道',
              `principal` varchar(50) DEFAULT NULL COMMENT '负责人',
              `tel` varchar(50) DEFAULT NULL COMMENT '电话',
              `other_contact` varchar(200) DEFAULT NULL COMMENT '其他联系方式',
              `bankcard_number` varchar(50) DEFAULT NULL COMMENT '银行卡号',
              `account_person` varchar(100) DEFAULT NULL COMMENT '开户人',
              `opening_bank` varchar(200) DEFAULT NULL COMMENT '开户行',
              `sub_branch` varchar(200) DEFAULT NULL COMMENT '支行名称',
              `opening_address` varchar(255) DEFAULT NULL COMMENT '开户地址',
              `bl_name` varchar(255) DEFAULT NULL COMMENT '营业执照名称',
              `bl_type` tinyint(2) DEFAULT '1' COMMENT '注册类型:1,个体户',
              `bl_number` varchar(200) DEFAULT NULL COMMENT '注册号',
              `bl_person` varchar(50) DEFAULT NULL COMMENT '法人代表',
              `bl_address` varchar(255) DEFAULT NULL COMMENT '营业地址',
              `bl_create_time` int(11) DEFAULT NULL COMMENT '注册时间',
              `bl_photo_url` varchar(255) DEFAULT NULL COMMENT '营业执照URL',
              `bl_audit` int(11) DEFAULT NULL COMMENT '注册资本',
              `bl_expiry_start` int(11) DEFAULT NULL COMMENT '有效期起始时间',
              `bl_expiry_end` int(11) DEFAULT NULL COMMENT '有效期结束时间',
              `bl_business` text COMMENT '营业范围',
              `created_at` int(11) DEFAULT '0' COMMENT '创建时间',
              `updated_at` int(11) DEFAULT '0' COMMENT '修改时间',
              `is_blacklist` int(3) DEFAULT 0 COMMENT '是否是黑名单：0正常，1黑名单',
              `audit_status` tinyint(1) DEFAULT 0 COMMENT '审核状态：0未审核，1通过，2不通过',
              `shop_count` int(11) DEFAULT 0 COMMENT '门店数量',
              `worker_count` int(11) DEFAULT 0 COMMENT '阿姨数量',
              `complain_coutn` int(11) DEFAULT 0 COMMENT '投诉数量',
              `level` varchar(50) DEFAULT NULL COMMENT '评级',
              `isdel` tinyint(1) DEFAULT 0 COMMENT '是否删除',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8

        ");
        $this->createIndex('shop_province_id', '{{%shop_manager}}', 'province_id');
        $this->createIndex('shop_city_id', '{{%shop_manager}}', 'city_id');
        
        $this->insert('{{%shop_manager}}', [
            'id'=>1,
            'name'=>'E家洁',
            'province_id'=>110000,
            'city_id'=>110100,
            'county_id'=>110105,
            'street'=>'光华SOHO 2单元708',
            'principal'=>'云涛',
            'tel'=>'123456',
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%shop_manager}}');
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
