<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_042915_create_table_city_types extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%city_type}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'编号\'' ,
            'city_type_city_id' => Schema::TYPE_STRING . '(30) NOT NULL COMMENT \'所属城市名称\'',
            'city_type_city_name' => Schema::TYPE_STRING . '(30) NOT NULL COMMENT \'所属城市名称\'',
            'city_type_category_id' => Schema::TYPE_SMALLINT . '(1) NOT NULL COMMENT \'对应服务品类编号\'',
            'city_type_category_name' => Schema::TYPE_SMALLINT . '(1) NOT NULL COMMENT \'对应服务品类名称\'',
            'city_type_id' => Schema::TYPE_SMALLINT . '(1) NOT NULL COMMENT \'对应服务分类编号\'',
            'city_type_name' => Schema::TYPE_SMALLINT . '(1) NOT NULL COMMENT \'对应服务分类名称\'',
            'city_type_display_index' => Schema::TYPE_SMALLINT . '(1) NOT NULL COMMENT \'首页是否显示\'',
            'city_type_display_index_order' => Schema::TYPE_SMALLINT . '(1) NOT NULL COMMENT \'首页显示顺序\'',
            'city_type_display_order' => Schema::TYPE_SMALLINT . '(1) NOT NULL COMMENT \'列表显示顺序\'',
            'city_type_start_time' => Schema::TYPE_SMALLINT . '(1) NOT NULL COMMENT \'开始服务时间即上班时间\'',
            'city_type_end_time' => Schema::TYPE_SMALLINT . '(1) NOT NULL COMMENT \'结束服务时间即下班时间\'',
            
            'city_type_service_time_slot' => Schema::TYPE_SMALLINT . '(1) NOT NULL COMMENT \'可服务时间段\'',
            'city_type_end_time' => Schema::TYPE_SMALLINT . '(1) NOT NULL COMMENT \'\'',
            'city_type_end_time' => Schema::TYPE_SMALLINT . '(1) NOT NULL COMMENT \'\'',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%city_type}}');
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
