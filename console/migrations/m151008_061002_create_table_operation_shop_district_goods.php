<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151008_061002_create_table_operation_shop_district_goods extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'商圈商品表\'';
        }
        $this->createTable('{{%operation_shop_district_goods}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'编号\'' ,
            'operation_shop_district_goods_name' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'商品名称\'',
            'operation_shop_district_goods_no' => Schema::TYPE_STRING . '(20) DEFAULT NULL COMMENT \'商品货号\'',
            
            'operation_goods_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'商品编号\'',
            'operation_shop_district_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'商圈id\'',
            'operation_shop_district_name' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'商圈名称\'',
            'operation_city_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'城市编号\'',
            'operation_city_name' => Schema::TYPE_STRING . '(50) DEFAULT NULL COMMENT \'城市名称\'',

            'operation_category_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'对应服务品类编号（所属分类编号冗余）\'',
            'operation_category_ids' => Schema::TYPE_STRING . '(255) DEFAULT NULL COMMENT \'对应服务品类的所有编号以“,”关联\'',
            'operation_category_name' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'对应服务品类名称（所属分类名称冗余）\'',
            'operation_shop_district_goods_introduction' => Schema::TYPE_TEXT . ' DEFAULT NULL COMMENT \'服务类型简介\'',
            'operation_shop_district_goods_english_name' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'服务类型英文名称\'',

            'operation_shop_district_goods_start_time' => Schema::TYPE_STRING . '(20) DEFAULT NULL COMMENT \'开始服务时间即开始时间\'',
            'operation_shop_district_goods_end_time' => Schema::TYPE_STRING . '(20) DEFAULT NULL COMMENT \'结束服务时间即结束时间\'',
            'operation_shop_district_goods_service_time_slot' => Schema::TYPE_TEXT . ' DEFAULT NULL COMMENT \'可服务时间段（序列化方式存储）\'',
            'operation_shop_district_goods_service_interval_time' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'服务间隔时间(单位：分钟)\'',
            'operation_shop_district_goods_service_estimate_time' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'预计服务时长(单位：分钟)\'',


            'operation_spec_info' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'序列化存储规格id\'',
            'operation_spec_strategy_unit' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'规格计量单位冗余\'',

            'operation_shop_district_goods_price' => Schema::TYPE_MONEY . ' DEFAULT 0 COMMENT \'售价\'',
            'operation_shop_district_goods_balance_price' => Schema::TYPE_MONEY . ' DEFAULT 0 COMMENT \'阿姨结算价格\'',
            'operation_shop_district_goods_additional_cost' => Schema::TYPE_MONEY . ' DEFAULT 0 COMMENT \'附加费用\'',
            
            'operation_shop_district_goods_lowest_consume_num' => Schema::TYPE_INTEGER . '(11) DEFAULT 0 COMMENT \'最低消费数量\'',
            'operation_shop_district_goods_lowest_consume' => Schema::TYPE_MONEY . ' DEFAULT 0 COMMENT \'最低消费价格\'',
            
            'operation_shop_district_goods_price_description' => Schema::TYPE_TEXT . ' DEFAULT NULL COMMENT \'价格备注\'',
            'operation_shop_district_goods_market_price' => Schema::TYPE_MONEY . ' DEFAULT 0 COMMENT \'市场价格\'',
            'operation_tags' => Schema::TYPE_TEXT . ' DEFAULT NULL COMMENT \'服务类型标签编号(序列化方式存储)\'',
            'operation_goods_img' => Schema::TYPE_TEXT . ' DEFAULT NULL COMMENT \'商品图片\'',
            'operation_shop_district_goods_app_ico' => Schema::TYPE_TEXT . ' DEFAULT NULL  COMMENT \'APP端图标(序列化方式存储|首页大图，首页小图，分类页小图，订单页小图)\'',
            'operation_shop_district_goods_pc_ico' => Schema::TYPE_TEXT . ' DEFAULT NULL  COMMENT \'PC端图标(序列化方式存储|首页推荐大图，更多推荐大图，下单页小图)\'',
            
            'operation_shop_district_goods_status' => Schema::TYPE_INTEGER . '(1) DEFAULT 1 COMMENT \'商品状态（1:上架 2:下架）\'',
            
            'created_at' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'编辑时间\'',
        ], $tableOptions);
        
         $this->execute(
            "insert into `ejj_operation_shop_district_goods` (`id`, `operation_shop_district_goods_name`, `operation_shop_district_goods_no`, `operation_goods_id`, `operation_shop_district_id`, `operation_shop_district_name`, `operation_city_id`, `operation_city_name`, `operation_category_id`, `operation_category_ids`, `operation_category_name`, `operation_shop_district_goods_introduction`, `operation_shop_district_goods_english_name`, `operation_shop_district_goods_start_time`, `operation_shop_district_goods_end_time`, `operation_shop_district_goods_service_time_slot`, `operation_shop_district_goods_service_interval_time`, `operation_shop_district_goods_service_estimate_time`, `operation_spec_info`, `operation_spec_strategy_unit`, `operation_shop_district_goods_price`, `operation_shop_district_goods_balance_price`, `operation_shop_district_goods_additional_cost`, `operation_shop_district_goods_lowest_consume_num`, `operation_shop_district_goods_lowest_consume`, `operation_shop_district_goods_price_description`, `operation_shop_district_goods_market_price`, `operation_tags`, `operation_goods_img`, `operation_shop_district_goods_app_ico`, `operation_shop_district_goods_pc_ico`, `operation_shop_district_goods_status`, `created_at`, `updated_at`) values('1','Apple iPhone 6s (A1700) 16G 金色 移动联通电信4G手机','14442474254','1','3','东城','110100','北京市','8','8','手机','iPhone，是苹果公司研发的智能手机，它搭载苹果公司研发的iOS操作系统。第一代iPhone于2007年1月9日由苹果公司前首席执行官史蒂夫·乔布斯发布，并在同年6月29日正式发售。第七代的iPhone 5S和iPhone 5C于2013年9月10日发布。','Apple iPhone 6s','14:00','15:00',NULL,'60','30','3','台','12.0000','64.0000','2.0000','13','156.0000','声明：下单1小时内未支付订单，京东有权取消订单；由于货源紧张，所有订单将根据各地实际到货时间陆续安排发货，如系统判定为经销商订单，京东有权取消订单。','11.0000','a:6:{i:0;s:6:\"手机\";i:1;s:6:\"iphone\";i:2;s:6:\"苹果\";i:3;s:5:\"Apple\";i:4;s:11:\"AppleiPhone\";i:5;s:13:\"AppleiPhone6s\";}','',NULL,NULL,'2','1444413773','1444413773'),
             ('2','Apple iPhone 6s (A1700) 16G 金色 移动联通电信4G手机','14442474254','1','3','东城','110100','北京市','8','8','手机','iPhone，是苹果公司研发的智能手机，它搭载苹果公司研发的iOS操作系统。第一代iPhone于2007年1月9日由苹果公司前首席执行官史蒂夫·乔布斯发布，并在同年6月29日正式发售。第七代的iPhone 5S和iPhone 5C于2013年9月10日发布。','Apple iPhone 6s','06:00','19:00',NULL,'60','30','3','台','20.0000','64.0000','2.0000','3','60.0000','声明：下单1小时内未支付订单，京东有权取消订单；由于货源紧张，所有订单将根据各地实际到货时间陆续安排发货，如系统判定为经销商订单，京东有权取消订单。','25.0000','a:6:{i:0;s:6:\"手机\";i:1;s:6:\"iphone\";i:2;s:6:\"苹果\";i:3;s:5:\"Apple\";i:4;s:11:\"AppleiPhone\";i:5;s:13:\"AppleiPhone6s\";}','',NULL,NULL,'1','1444894800','1444894800'),
             ('3','Apple iPhone 6s (A1700) 16G 金色 移动联通电信4G手机','14442474254','1','4','西城区','110100','北京市','8','8','手机','iPhone，是苹果公司研发的智能手机，它搭载苹果公司研发的iOS操作系统。第一代iPhone于2007年1月9日由苹果公司前首席执行官史蒂夫·乔布斯发布，并在同年6月29日正式发售。第七代的iPhone 5S和iPhone 5C于2013年9月10日发布。','Apple iPhone 6s','06:00','19:00',NULL,'60','30','3','台','20.0000','64.0000','2.0000','3','60.0000','声明：下单1小时内未支付订单，京东有权取消订单；由于货源紧张，所有订单将根据各地实际到货时间陆续安排发货，如系统判定为经销商订单，京东有权取消订单。','25.0000','a:6:{i:0;s:6:\"手机\";i:1;s:6:\"iphone\";i:2;s:6:\"苹果\";i:3;s:5:\"Apple\";i:4;s:11:\"AppleiPhone\";i:5;s:13:\"AppleiPhone6s\";}','',NULL,NULL,'1','1444894800','1444894800'),
             ('4','Apple iPhone 6s (A1700) 16G 金色 移动联通电信4G手机','14442474254','1','5','朝阳','110100','北京市','8','8','手机','iPhone，是苹果公司研发的智能手机，它搭载苹果公司研发的iOS操作系统。第一代iPhone于2007年1月9日由苹果公司前首席执行官史蒂夫·乔布斯发布，并在同年6月29日正式发售。第七代的iPhone 5S和iPhone 5C于2013年9月10日发布。','Apple iPhone 6s','06:00','19:00',NULL,'60','30','3','台','20.0000','64.0000','2.0000','3','60.0000','声明：下单1小时内未支付订单，京东有权取消订单；由于货源紧张，所有订单将根据各地实际到货时间陆续安排发货，如系统判定为经销商订单，京东有权取消订单。','25.0000','a:6:{i:0;s:6:\"手机\";i:1;s:6:\"iphone\";i:2;s:6:\"苹果\";i:3;s:5:\"Apple\";i:4;s:11:\"AppleiPhone\";i:5;s:13:\"AppleiPhone6s\";}','',NULL,NULL,'1','1444894800','1444894800');"
        );
    }

    public function down()
    {
        $this->dropTable('{{%operation_shop_district_goods}}');
    }
}
