<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_075922_create_table_op_category_type extends Migration
{
    public function up(){
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'服务类型表\'';
        }
        $this->createTable('{{%op_category_type}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'编号\'' ,
            'category_type_name' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'服务类型名称\'',
            'category_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'对应服务品类编号（服务类型的上级编号冗余）\'',
            'category_name' => Schema::TYPE_STRING . '(11) DEFAULT NULL COMMENT \'对应服务品类名称（服务类型的上级名称冗余）\'',
            'category_type_introduction' => Schema::TYPE_TEXT . ' DEFAULT NULL COMMENT \'服务类型简介\'',
            'category_type_english_name' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'服务类型英文名称\'',
            
            'category_type_start_time' => Schema::TYPE_STRING . '(20) DEFAULT NULL COMMENT \'开始服务时间即上班时间\'',
            'category_type_end_time' => Schema::TYPE_STRING . '(20) DEFAULT NULL COMMENT \'结束服务时间即下班时间\'',
            'category_type_service_time_slot' => Schema::TYPE_TEXT . ' DEFAULT NULL COMMENT \'可服务时间段（序列化方式存储）\'',
            'category_type_service_interval_time' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'服务间隔时间(单位：秒)\'',
            
            'price_strategy_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'价格策略编号\'',
            'price_strategy_name' => Schema::TYPE_STRING . '(30) DEFAULT NULL COMMENT \'价格策略名称\'',
            'category_type_price' => Schema::TYPE_MONEY . ' DEFAULT 0 COMMENT \'价格\'',
            'category_type_balance_price' => Schema::TYPE_MONEY . ' DEFAULT 0 COMMENT \'阿姨结算价格\'',
            'category_type_additional_cost' => Schema::TYPE_MONEY . ' DEFAULT 0 COMMENT \'附加费用\'',
            'category_type_lowest_consume' => Schema::TYPE_MONEY . ' DEFAULT 0 COMMENT \'最低消费\'',
            'category_type_price_description' => Schema::TYPE_TEXT . ' DEFAULT NULL COMMENT \'价格备注\'',
            'category_type_market_price' => Schema::TYPE_MONEY . ' DEFAULT 0 COMMENT \'市场价格\'',
            'tags' => Schema::TYPE_TEXT . ' DEFAULT NULL COMMENT \'服务类型标签编号(序列化方式存储)\'',
            'category_type_app_ico' => Schema::TYPE_TEXT . '  COMMENT \'APP端图标(序列化方式存储|首页大图，首页小图，分类页小图，订单页小图)\'',
            'category_type_pc_ico' => Schema::TYPE_TEXT . '  COMMENT \'PC端图标(序列化方式存储|首页推荐大图，更多推荐大图，下单页小图)\'',
            
        ], $tableOptions);
    }

    public function down(){
        $this->dropTable('{{%op_category_type}}');
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
