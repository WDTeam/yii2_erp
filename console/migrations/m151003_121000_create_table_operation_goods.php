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

            'operation_price_strategy_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'价格策略编号\'',
            'operation_price_strategy_name' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'价格策略名称\'',
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

    }

    public function down()
    {
        $this->dropTable('{{%operation_goods}}');
    }
}
