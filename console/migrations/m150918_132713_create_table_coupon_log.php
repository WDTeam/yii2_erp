<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_132713_create_table_coupon_log extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'优惠券日志表\'';
        }
        $this->createTable('{{%coupon_log}}', [
                'id'=>  Schema::TYPE_PK.'(8) NOT NULL AUTO_INCREMENT COMMENT \'主键\'',
				'customer_code'=>  Schema::TYPE_STRING. '(64) DEFAULT 0 COMMENT \'流水号\'',
                'customer_id'=>  Schema::TYPE_INTEGER. '(8) DEFAULT 0 COMMENT \'客户id\'',
		'order_id'=>  Schema::TYPE_INTEGER. '(8) DEFAULT 0 COMMENT \'订单id\'',
		'coupon_id'=>  Schema::TYPE_INTEGER. '(8) DEFAULT 0 COMMENT \'优惠规则id\'',
		'coupon_code_id'=>  Schema::TYPE_INTEGER. '(8) DEFAULT 0 COMMENT \'优惠码id\'',
		'coupon_code'=>  Schema::TYPE_STRING. '(255) DEFAULT NULL COMMENT \'优惠码\'',
		'coupon_name'=>  Schema::TYPE_STRING. '(255) DEFAULT NULL COMMENT \'优惠券名称\'',
		'coupon_price'=>  Schema::TYPE_DECIMAL. '(8,2) DEFAULT 0 COMMENT \'优惠券价值\'',

                'coupon_log_type'=>  Schema::TYPE_SMALLINT.'(4) NOT NULL COMMENT \'优惠券日志类型1为领取2为使用3为退还\'',
		'coupon_log_type_name'=>  Schema::TYPE_STRING.'(255) NOT NULL COMMENT \'优惠券日志类型名称\'',
                'coupon_log_price'=>  Schema::TYPE_DECIMAL.'(8,2) NOT NULL COMMENT \'实际优惠或者退还金额\'',
		
                'created_at'=> Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'创建时间\'',
                'updated_at'=> Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'更新时间\'',
                'is_del'=> Schema::TYPE_SMALLINT . '(4) NOT NULL DEFAULT 0 COMMENT \'是否逻辑删除\'',
            ], $tableOptions);
			$this->createIndex('customer_code','{{%coupon_log}}','customer_code');
			$this->createIndex('customer_id','{{%coupon_log}}','customer_id');
			$this->createIndex('order_id','{{%coupon_log}}','order_id');
			$this->createIndex('coupon_id','{{%coupon_log}}','coupon_id');
			$this->createIndex('coupon_code','{{%coupon_log}}','coupon_code');
        
    }

    public function down()
    {
        $this->dropTable('{{%coupon_log}}');

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
