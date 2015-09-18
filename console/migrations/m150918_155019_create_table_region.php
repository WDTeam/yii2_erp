<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_155019_create_table_region extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions ='CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'订单状态表\'';
        }


        $this->createTable('{{%region}}', [
            'id'=> Schema::TYPE_PK.' NOT NULL AUTO_INCREMENT COMMENT \'ID\'',
            'parent_id' => Schema::TYPE_INTEGER.'(10) NOT NULL DEFAULT 0 COMMENT \'上级ID\'',
            'region_area_name' => Schema::TYPE_STRING.'(20) NOT NULL COMMENT \'地区名称\'',
            'region_province_name'=> Schema::TYPE_STRING.'(20) DEFAULT NULL COMMENT \'省份名称\'',
            'region_city_name'=> Schema::TYPE_STRING.'(50) DEFAULT NULL COMMENT \'城市名称\'',
            'region_area_ename'=> Schema::TYPE_STRING.'(50) DEFAULT NULL COMMENT \'地区拼音\'',
            'region_province_ename'=> Schema::TYPE_STRING.'(50) DEFAULT NULL COMMENT \'省份拼音\'',
            'region_city_ename'=> Schema::TYPE_STRING.'(50) DEFAULT NULL COMMENT \'城市拼音\'',
            'region_level' => Schema::TYPE_SMALLINT.'(2) DEFAULT NULL COMMENT \'地区级别\'',
            'created_at' => Schema::TYPE_INTEGER.'(10) NOT NULL DEFAULT 0 COMMENT \'创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER.'(10) NOT NULL DEFAULT 0 COMMENT \'更新时间\'',
            'isdel' => Schema::TYPE_SMALLINT.'(1) unsigned NOT NULL DEFAULT 0',
        ], $tableOptions);

//        $this->batchInsert('{{%}}',
//            ['id','order_status_info_name','order_status_flow','order_status_oper','order_status_oper_man','created_at','updated_at','isdel'],
//            ['1','初始化']);
    }

    public function down()
    {
        $this->dropTable('{{%region}}');
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
