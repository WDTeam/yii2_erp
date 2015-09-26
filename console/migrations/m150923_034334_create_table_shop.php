<?php

use yii\db\Schema;
use yii\db\Migration;

class m150923_034334_create_table_shop extends Migration
{
    public function up()
    {
        \Yii::$app->db->createCommand("
            CREATE TABLE IF NOT EXISTS {{%shop}} (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `name` varchar(100) NOT NULL COMMENT '店名',
              `shop_manager_id` int(11) NOT NULL DEFAULT '0' COMMENT '归属家政ID',
              `province_name` varchar(50) NOT NULL COMMENT '省份',
              `city_name` varchar(50) NOT NULL COMMENT '城市',
              `county_name` varchar(50) DEFAULT NULL COMMENT '县',
              `street` varchar(255) NOT NULL COMMENT '办公街道',
              `principal` varchar(50) NOT NULL COMMENT '负责人',
              `tel` varchar(50) NOT NULL COMMENT '电话',
              `other_contact` varchar(200) DEFAULT NULL COMMENT '其他联系方式',
              `bankcard_number` varchar(50) DEFAULT NULL COMMENT '银行卡号',
              `account_person` varchar(100) DEFAULT NULL COMMENT '开户人',
              `opening_bank` varchar(200) DEFAULT NULL COMMENT '开户行',
              `sub_branch` varchar(200) DEFAULT NULL COMMENT '支行名称',
              `opening_address` varchar(255) DEFAULT NULL COMMENT '开户地址',
              `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
              `update_at` int(11) DEFAULT '0' COMMENT '修改时间',
              `is_blacklist` int(3) DEFAULT '0' COMMENT '是否是黑名单：0正常，1黑名单',
              `blacklist_time` int(11) DEFAULT '0' COMMENT '加入黑名单时间',
              `blacklist_cause` varchar(255) DEFAULT NULL COMMENT '黑名单原因',
              `audit_status` tinyint(1) DEFAULT '0' COMMENT '审核状态：0未审核，1通过，2不通过',
              `worker_count` int(11) DEFAULT '0' COMMENT '阿姨数量',
              `complain_coutn` int(11) DEFAULT '0' COMMENT '投诉数量',
              `level` varchar(50) DEFAULT NULL COMMENT '评级',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            
        ")->execute();
    }

    public function down()
    {
        $this->dropTable('{{%shop}}');
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
