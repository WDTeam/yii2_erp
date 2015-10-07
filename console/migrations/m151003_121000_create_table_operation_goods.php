<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151003_121000_create_table_operation_goods extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'商品表\'';
        }
        $this->createTable('{{%operation_goods}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'编号\'' ,
            'operation_goods_name' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'商品名称\'',
            'operation_goods_no' => Schema::TYPE_STRING . '(20) DEFAULT NULL COMMENT \'商品货号\'',

            'operation_category_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'对应服务品类编号（所属分类编号冗余）\'',
            'operation_category_ids' => Schema::TYPE_STRING . '(255) DEFAULT NULL COMMENT \'对应服务品类的所有编号以“,”关联\'',
            'operation_category_name' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'对应服务品类名称（所属分类名称冗余）\'',
            'operation_goods_introduction' => Schema::TYPE_TEXT . ' DEFAULT NULL COMMENT \'服务类型简介\'',
            'operation_goods_english_name' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'服务类型英文名称\'',

            'operation_goods_start_time' => Schema::TYPE_STRING . '(20) DEFAULT NULL COMMENT \'开始服务时间即开始时间\'',
            'operation_goods_end_time' => Schema::TYPE_STRING . '(20) DEFAULT NULL COMMENT \'结束服务时间即结束时间\'',
            'operation_goods_service_time_slot' => Schema::TYPE_TEXT . ' DEFAULT NULL COMMENT \'可服务时间段（序列化方式存储）\'',
            'operation_goods_service_interval_time' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'服务间隔时间(单位：分钟)\'',
            'operation_goods_service_estimate_time' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'预计服务时长(单位：分钟)\'',


            'operation_spec_info' => Schema::TYPE_TEXT . ' DEFAULT NULL COMMENT \'序列化存储规格\'',

            'operation_goods_price' => Schema::TYPE_MONEY . ' DEFAULT 0 COMMENT \'售价\'',
            'operation_goods_balance_price' => Schema::TYPE_MONEY . ' DEFAULT 0 COMMENT \'阿姨结算价格\'',
            'operation_goods_additional_cost' => Schema::TYPE_MONEY . ' DEFAULT 0 COMMENT \'附加费用\'',
            'operation_goods_lowest_consume' => Schema::TYPE_MONEY . ' DEFAULT 0 COMMENT \'最低消费\'',
            'operation_goods_price_description' => Schema::TYPE_TEXT . ' DEFAULT NULL COMMENT \'价格备注\'',
            'operation_goods_market_price' => Schema::TYPE_MONEY . ' DEFAULT 0 COMMENT \'市场价格\'',
            'operation_tags' => Schema::TYPE_TEXT . ' DEFAULT NULL COMMENT \'服务类型标签编号(序列化方式存储)\'',
            'operation_goods_app_ico' => Schema::TYPE_TEXT . ' DEFAULT NULL  COMMENT \'APP端图标(序列化方式存储|首页大图，首页小图，分类页小图，订单页小图)\'',
            'operation_goods_pc_ico' => Schema::TYPE_TEXT . ' DEFAULT NULL  COMMENT \'PC端图标(序列化方式存储|首页推荐大图，更多推荐大图，下单页小图)\'',

            'created_at' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'编辑时间\'',
        ], $tableOptions);
$this->execute(
            "INSERT INTO {{%operation_goods}} (`id`, `operation_goods_name`, `operation_goods_no`, `operation_category_id`, `operation_category_ids`, `operation_category_name`, `operation_goods_introduction`, `operation_goods_english_name`, `operation_goods_start_time`, `operation_goods_end_time`, `operation_goods_service_time_slot`, `operation_goods_service_interval_time`, `operation_goods_service_estimate_time`, `operation_spec_info`, `operation_goods_price`, `operation_goods_balance_price`, `operation_goods_additional_cost`, `operation_goods_lowest_consume`, `operation_goods_price_description`, `operation_goods_market_price`, `operation_tags`, `operation_goods_app_ico`, `operation_goods_pc_ico`, `created_at`, `updated_at`) VALUES (1, 'Apple iPhone 6s (A1700) 16G 金色 移动联通电信4G手机', '14442474254', 8, '8', '手机', 'iPhone，是苹果公司研发的智能手机，它搭载苹果公司研发的iOS操作系统。第一代iPhone于2007年1月9日由苹果公司前首席执行官史蒂夫·乔布斯发布，并在同年6月29日正式发售。第七代的iPhone 5S和iPhone 5C于2013年9月10日发布。', 'Apple iPhone 6s', '09:00', '20:00 ', NULL, 60, 30, 'a:3:{s:2:\"id\";s:1:\"3\";s:19:\"operation_spec_name\";s:6:\"iphone\";s:21:\"operation_spec_values\";a:4:{i:0;s:12:\"16G合约机\";i:1;s:15:\"32G非合约机\";i:2;s:11:\"64合约机\";i:3;s:13:\"128G合约机\";}}', 62.0000, 64.0000, 2.0000, 372.0000, '声明：下单1小时内未支付订单，京东有权取消订单；由于货源紧张，所有订单将根据各地实际到货时间陆续安排发货，如系统判定为经销商订单，京东有权取消订单。', 61.0000, 'a:6:{i:0;s:6:\"手机\";i:1;s:6:\"iphone\";i:2;s:6:\"苹果\";i:3;s:5:\"Apple\";i:4;s:11:\"AppleiPhone\";i:5;s:13:\"AppleiPhone6s\";}', 'a:4:{s:36:\"operation_goods_app_homepage_max_ico\";s:65:\"http://7b1f97.com1.z0.glb.clouddn.com/1444248316815156157afc182f6\";s:36:\"operation_goods_app_homepage_min_ico\";s:65:\"http://7b1f97.com1.z0.glb.clouddn.com/1444248317329656157afd1d409\";s:32:\"operation_goods_app_type_min_ico\";s:65:\"http://7b1f97.com1.z0.glb.clouddn.com/1444248317111156157afd8a78e\";s:33:\"operation_goods_app_order_min_ico\";s:65:\"http://7b1f97.com1.z0.glb.clouddn.com/1444248318828656157afe07220\";}', 'a:3:{s:35:\"operation_goods_pc_homepage_max_ico\";s:65:\"http://7b1f97.com1.z0.glb.clouddn.com/1444248318876956157afe3dd0a\";s:31:\"operation_goods_pc_more_max_ico\";s:65:\"http://7b1f97.com1.z0.glb.clouddn.com/1444248318367556157afe8e66a\";s:39:\"operation_goods_pc_submit_order_min_ico\";s:65:\"http://7b1f97.com1.z0.glb.clouddn.com/1444248318983156157afecefcb\";}', 1444240457, 1444248319);"
        );
    }

    public function down()
    {
        $this->dropTable('{{%operation_goods}}');
    }
}
