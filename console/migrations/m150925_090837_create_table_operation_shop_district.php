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
            'operation_shop_district_status' => Schema::TYPE_INTEGER . '(1) DEFAULT 1 COMMENT \'是否已上线(1: 未上线 2: 已上线)\'',
            'operation_shop_district_latitude_longitude' => Schema::TYPE_TEXT . ' DEFAULT NULL COMMENT \'商圈经纬度（序列化存储）\'',
            'created_at' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'编辑时间\'',
        ], $tableOptions);
    }

    public function down(){
        $this->dropTable('{{%operation_boot_page_city}}');
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
