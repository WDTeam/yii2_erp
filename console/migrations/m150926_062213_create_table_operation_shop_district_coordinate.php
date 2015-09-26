<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m150926_062213_create_table_operation_shop_district_coordinate extends Migration
{
    public function up(){
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'商圈经纬度表\'';
        }
        $this->createTable('{{%operation_shop_district_coordinate}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT COMMENT \'编号\'' ,
            'operation_shop_district_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'商圈id\'',
            'operation_shop_district_name' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'商圈名称\'',
            'operation_city_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'城市编号\'',
            'operation_city_name' => Schema::TYPE_STRING . '(50) DEFAULT NULL COMMENT \'城市名称\'',
            'operation_shop_district_coordinate_start_longitude' => Schema::TYPE_STRING . '(50) DEFAULT NULL COMMENT \'开始经度\'',
            'operation_shop_district_coordinate_start_latitude' => Schema::TYPE_STRING . '(50) DEFAULT NULL COMMENT \'开始纬度\'',
            'operation_shop_district_coordinate_end_longitude' => Schema::TYPE_STRING . '(50) DEFAULT NULL COMMENT \'结束经度\'',
            'operation_shop_district_coordinate_end_latitude' => Schema::TYPE_STRING . '(50) DEFAULT NULL COMMENT \'结束纬度\'',
            
            'created_at' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'编辑时间\'',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%operation_shop_district_coordinate}}');
    }
}
