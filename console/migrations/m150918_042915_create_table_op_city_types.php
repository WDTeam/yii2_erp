<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_042915_create_table_op_city_types extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'城市所具备的服务表\'';
        }
        
        $this->createTable('{{%op_city_type}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT COMMENT \'编号\'' ,
            'city_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'所属城市编号\'',
            'city_name' => Schema::TYPE_STRING . '(30) DEFAULT NULL COMMENT \'所属城市名称\'',
            'category_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'对应服务品类编号（服务类型的上级编号冗余）\'',
            'category_name' => Schema::TYPE_STRING . '(30) DEFAULT NULL COMMENT \'对应服务品类名称（服务类型的上级名称冗余）\'',
            'category_type_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'对应服务类型编号\'',
            'category_type_name' => Schema::TYPE_STRING . '(30) DEFAULT NULL COMMENT \'对应服务类型名称\'',
            'city_type_display_index' => Schema::TYPE_SMALLINT . '(1) DEFAULT 2 COMMENT \'首页是否显示(1显示，2不显示)\'',
            'city_type_display_index_order' => Schema::TYPE_INTEGER . '(11) DEFAULT 9999 COMMENT \'首页显示顺序\'',
            'city_type_display_order' => Schema::TYPE_INTEGER . '(11) DEFAULT 9999 COMMENT \'列表显示顺序\'',
            
            'city_type_start_time' => Schema::TYPE_STRING . '(20) DEFAULT NULL COMMENT \'开始服务时间即上班时间\'',
            'city_type_end_time' => Schema::TYPE_STRING . '(20) DEFAULT NULL COMMENT \'结束服务时间即下班时间\'',
            'city_type_service_time_slot' => Schema::TYPE_TEXT . ' DEFAULT NULL COMMENT \'可服务时间段（序列化方式存储）\'',
            'city_type_service_interval_time' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'服务间隔时间(单位：秒)\'',
            
            'price_strategy_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'价格策略编号\'',
            'price_strategy_name' => Schema::TYPE_STRING . '(30) DEFAULT NULL COMMENT \'价格策略名称\'',
            'city_type_price' => Schema::TYPE_MONEY . ' DEFAULT 0 COMMENT \'价格\'',
            'city_type_balance_price' => Schema::TYPE_MONEY . ' DEFAULT 0 COMMENT \'阿姨结算价格\'',
            'city_type_additional_cost' => Schema::TYPE_MONEY . ' DEFAULT 0 COMMENT \'附加费用\'',
            'city_type_lowest_consume' => Schema::TYPE_MONEY . ' DEFAULT 0 COMMENT \'最低消费\'',
            'city_type_price_description' => Schema::TYPE_TEXT . ' DEFAULT NULL COMMENT \'价格备注\'',
            'city_type_market_price' => Schema::TYPE_MONEY . ' DEFAULT 0 COMMENT \'市场价格\'',
            'created_at' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'编辑时间\'',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%op_city_type}}');
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
