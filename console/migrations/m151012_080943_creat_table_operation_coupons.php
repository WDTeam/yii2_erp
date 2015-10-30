<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151012_080943_creat_table_operation_coupons extends Migration
{
    public function up()
    {
//        $tableOptions = null;
//        if ($this->db->driverName === 'mysql') {
//            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'商圈商品表\'';
//        }
//        $this->createTable('{{%operation_coupons}}', [
//            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'编号\'' ,
//            'operation_shop_district_goods_name' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'优惠券名称\'',
//            'operation_shop_district_goods_no' => Schema::TYPE_STRING . '(20) DEFAULT NULL COMMENT \'商品货号\'',
//            
//            'created_at' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'创建时间\'',
//            'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'编辑时间\'',
//        ], $tableOptions);
    }

    public function down()
    {
//        $this->dropTable('{{%operation__coupons}}');
    }
}
