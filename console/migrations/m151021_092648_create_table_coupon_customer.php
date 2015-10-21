<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151021_092648_create_table_coupon_customer extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'优惠规则表\'';
        }
        $this->createTable('{{%coupon_customer}}', [
                'id' => Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'主键\'',

		'customer_id'=>  Schema::TYPE_INTEGER. '(8) DEFAULT 0 COMMENT \'客户id\'',
		'order_id'=>  Schema::TYPE_INTEGER. '(8) DEFAULT 0 COMMENT \'订单id\'',
		'coupon_id'=>  Schema::TYPE_INTEGER. '(8) DEFAULT 0 COMMENT \'优惠规则id\'',
		'coupon_code_id'=>  Schema::TYPE_INTEGER. '(8) DEFAULT 0 COMMENT \'优惠码id\'',
		'coupon_code'=>  Schema::TYPE_STRING. '(255) DEFAULT NULL COMMENT \'优惠码\'',
		'coupon_name'=>  Schema::TYPE_STRING. '(255) DEFAULT NULL COMMENT \'优惠券名称\'',
		'coupon_price'=>  Schema::TYPE_DECIMAL. '(8,2) DEFAULT 0 COMMENT \'优惠券价值\'',
		
		'expirate_at'=>  Schema::TYPE_INTEGER . '(11) DEFAULT 0 COMMENT \'过期时间\'',
		'is_used'=>Schema::TYPE_SMALLINT . '(4) DEFAULT 0 COMMENT \'是否已经使用\'',
		
	        'created_at'=>  Schema::TYPE_INTEGER . '(11) DEFAULT 0 COMMENT \'创建时间\'',
	        'updated_at'=> Schema::TYPE_INTEGER . '(11) DEFAULT 0 COMMENT \'更新时间\'',
	        'is_del'=> Schema::TYPE_SMALLINT . '(4) DEFAULT 0 COMMENT \'是否逻辑删除\'',
		
	    ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%coupon_customer}}');

        return true;
    }
}
