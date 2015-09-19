<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_132651_ejj_coupon extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'优惠券\'';
        }
        $this->createTable('{{%coupon}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'主键\'',
            'coupon_code'=>  Schema::TYPE_STRING. '(16) DEFAULT NULL COMMENT \'优惠码\'',
            // 'coupon_exchange_status'=> Schema::TYPE_SMALLINT . '(4) NOT NULL COMMENT \'兑换状态，1为未兑换，2为已兑换\'',
            'coupon_bundle_status'=> Schema::TYPE_SMALLINT . '(4) NOT NULL COMMENT \'优惠券或者优惠码绑定状态，1为未绑定用户，2为已绑定用户\'',
            'coupon_use_status'=>  Schema::TYPE_SMALLINT . '(4) NOT NULL COMMENT \'使用状态，1为未使用，2为已使用\'',
            'coupon_type'=>  Schema::TYPE_SMALLINT . '(4) NOT NULL COMMENT \'1,满减，2立减\'',
            'coupon_use_time_type'=>  Schema::TYPE_SMALLINT. '(4) NOT NULL COMMENT \'优惠券使用时间类型，1为按照时长，2为按照起止时间\'',
            'coupon_min_price'=>  Schema::TYPE_DECIMAL. '(8,2) NOT NULL DEFAULT 0.00 COMMENT \'最小订单金额\'',
            'coupon_price'=>  Schema::TYPE_DECIMAL. '(8,2) NOT NULL COMMENT \'优惠券价值\'',
            'customer_id'=>  Schema::TYPE_INTEGER.'(8) NOT NULL',
            'coupon_city_limit'=>  Schema::TYPE_INTEGER . '(4) NOT NULL COMMENT \'城市限制，1为城市单一限制，2为不限\'',
            'city_id'=> Schema::TYPE_INTEGER.'(8) NOT NULL',
            'coupon_customer_limit'=>  Schema::TYPE_INTEGER . '(4) NOT NULL COMMENT \'新老用户限制，1为只限新用户，2为只限老用户，3为不限\'',
            'service_id'=>  Schema::TYPE_STRING.'(32) NOT NULL COMMENT \'关联服务\'',
            'coupon_service_limit'=>  Schema::TYPE_SMALLINT. '(4) NOT NULL COMMENT \'是否有服务限制，1为单一服务限制，2为不限\'',
            'coupon_exchangeable_begin_time'=>  Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT \'优惠券开始时间\'',
            'coupon_exchangeable_end_time'=>  Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT \'优惠券兑换结束时间\'',
            'coupon_code_get_time'=>  Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT \'用户绑定时间\'',
            'coupon_exchange_time'=>  Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT \'优惠券兑换时间\'',
            'created_at'=>  Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT \'创建时间\'',
            'updated_at'=> Schema::TYPE_INTEGER . '(11) NOT NULL',
            'coupon_useable_begin_time'=>  Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT \'开始时间\'',
            'coupon_useable_end_time'=>  Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT \'结束时间\'',
            'coupon_use_time'=>  Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT \'优惠券使用时间\'',
            ]);
    }

    public function down()
    {
        $this->dropTable('{{%coupon}}');
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
