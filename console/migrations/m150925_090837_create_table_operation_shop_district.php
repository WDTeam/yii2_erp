<?php

use yii\db\Schema;
use yii\db\Migration;

class m150925_090837_create_table_operation_shop_district extends Migration
{
    public function up(){
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'城市商圈表\'';
        }
        $this->createTable('{{%operation_shop_district}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT COMMENT \'编号\'' ,
            'operation_shop_district_name' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'商圈名称\'',
            'operation_city_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'城市编号\'',
            'operation_city_name' => Schema::TYPE_STRING . '(50) DEFAULT NULL COMMENT \'城市名称\'',

            'operation_area_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'区域id\'',
            'operation_area_name' => Schema::TYPE_STRING . '(50) DEFAULT NULL COMMENT \'区域名称\'',

            'operation_shop_district_status' => Schema::TYPE_INTEGER . '(1) DEFAULT 1 COMMENT \'是否已上线(1: 未上线 2: 已上线)\'',
            'created_at' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'编辑时间\'',
        ], $tableOptions);
    }

    public function down(){
        $this->dropTable('{{%operation_shop_district}}');

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
