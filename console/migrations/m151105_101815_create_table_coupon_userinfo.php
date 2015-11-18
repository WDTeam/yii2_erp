<?php

use jamband\schemadump\Migration;
use yii\db\Schema;

class m151105_101815_create_table_coupon_userinfo extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'优惠码用户绑定表\'';
        }

        $this->createTable('{{%coupon_userinfo}}', [
            'id' => Schema::TYPE_PK . ' NOT NULL AUTO_INCREMENT COMMENT \'ID\'',
            'customer_id' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0 COMMENT \'客户id\'',
            'customer_tel' => Schema::TYPE_STRING . '(11) NOT NULL DEFAULT 0 COMMENT \'客户手机号\'',
			'customer_code' => Schema::TYPE_STRING . '(64) NOT NULL DEFAULT \'\' COMMENT \'流水号\'',
            'coupon_userinfo_id' => Schema::TYPE_INTEGER . '(8) NOT NULL DEFAULT 0 COMMENT \'优惠规则id\'',
            'coupon_userinfo_code' => Schema::TYPE_STRING . '(40) NOT NULL DEFAULT \'\' COMMENT \'优惠码\'',
            'coupon_userinfo_name' => Schema::TYPE_STRING . '(100) NOT NULL DEFAULT \'\' COMMENT \'优惠券名称\'',
            'coupon_userinfo_price' => Schema::TYPE_DECIMAL . '(8,2) DEFAULT 0.00 COMMENT \'优惠券价值\'',
            'coupon_userinfo_gettime' => Schema::TYPE_INTEGER . '(11) DEFAULT 0 COMMENT \'领取时间\'',
            'coupon_userinfo_usetime' => Schema::TYPE_INTEGER . '(11) DEFAULT 0 COMMENT \'使用时间\'',
            'couponrule_use_start_time' => Schema::TYPE_INTEGER . '(11) DEFAULT 0 COMMENT \'优惠券的用户可使用的开始时间\'',
            'couponrule_use_end_time' => Schema::TYPE_INTEGER . '(11) DEFAULT 0 COMMENT \'过期时间\'',

            'couponrule_classify' => Schema::TYPE_INTEGER . '(4) NOT NULL DEFAULT 0 COMMENT \'1 一码一用  2 一码多用\'',
            'couponrule_category' => Schema::TYPE_INTEGER . '(4) DEFAULT 0 COMMENT \'优惠券分类1为一般优惠券2为赔付优惠券\'',
            'couponrule_type' => Schema::TYPE_INTEGER . '(4) DEFAULT 0 COMMENT \'实收金额优惠券类型1为全网优惠券2为类别优惠券3为商品优惠券\'',
            'couponrule_service_type_id' => Schema::TYPE_INTEGER . '(4) DEFAULT 0 COMMENT \'服务类别id\'',
            'couponrule_commodity_id' => Schema::TYPE_INTEGER . '(4) DEFAULT 0 COMMENT \'如果是商品优惠券id\'',
            'couponrule_city_limit' => Schema::TYPE_INTEGER . '(4) DEFAULT 0 COMMENT \'城市限制1为不限2为单一城市限制\'',
            'couponrule_city_id' => Schema::TYPE_INTEGER . '(8) DEFAULT 0 COMMENT \'关联城市\'',
            'couponrule_customer_type' => Schema::TYPE_STRING . '(40) DEFAULT 0 COMMENT \'适用客户类别逗号分割1为所有用户2为新用户3为老用户4会员5为非会员\'',
            'couponrule_use_end_days' => Schema::TYPE_INTEGER . '(2) DEFAULT 1 COMMENT \'领取后过期天数\'',
            'couponrule_promote_type' => Schema::TYPE_INTEGER . '(11) DEFAULT 0 COMMENT \'优惠券优惠类型1为立减2为满减3为每减\'',
            'couponrule_order_min_price' => Schema::TYPE_DECIMAL . '(8,2) DEFAULT \'0.00\' COMMENT \'最小金额\'',
            'couponrule_price' => Schema::TYPE_DECIMAL . '(8,2) DEFAULT \'0.00\' COMMENT \'满减或每减时订单最小金额\'',
            'order_code' => Schema::TYPE_STRING . '(64) DEFAULT 0 COMMENT \'如果已经使用订单号\'',
            'is_disabled' => Schema::TYPE_BOOLEAN . '(1) DEFAULT 0 COMMENT \'是否禁用\'',
            'system_user_id' => Schema::TYPE_INTEGER . '(4) DEFAULT 0 COMMENT \'绑定人id\'',
            'system_user_name' => Schema::TYPE_STRING . '(40) DEFAULT \'\' COMMENT \'绑定人名称\'',
            'is_used' => Schema::TYPE_SMALLINT . '(4) DEFAULT 0 COMMENT \'是否已经使用\'',
            'created_at' => Schema::TYPE_INTEGER . '(11) unsigned NOT NULL DEFAULT 0 COMMENT \'创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11) unsigned NOT NULL DEFAULT 0 COMMENT \'修改时间\'',
            'is_del' => Schema::TYPE_SMALLINT . '(1) unsigned NOT NULL DEFAULT 0 COMMENT \'状态\'',
        ], $tableOptions);
		$this->createIndex('customer_tel','{{%coupon_userinfo}}','customer_tel');
        $this->createIndex('coupon_userinfo_id','{{%coupon_userinfo}}','coupon_userinfo_id');
        $this->createIndex('coupon_userinfo_code','{{%coupon_userinfo}}','coupon_userinfo_code');
        $this->createIndex('couponrule_use_start_time','{{%coupon_userinfo}}','couponrule_use_start_time');
        $this->createIndex('couponrule_use_end_time','{{%coupon_userinfo}}','couponrule_use_end_time');
        $this->createIndex('couponrule_classify','{{%coupon_userinfo}}','couponrule_classify');
        $this->createIndex('couponrule_category','{{%coupon_userinfo}}','couponrule_category');
		$this->createIndex('couponrule_type','{{%coupon_userinfo}}','couponrule_type');
		$this->createIndex('couponrule_promote_type','{{%coupon_userinfo}}','couponrule_promote_type');
		$this->createIndex('is_disabled','{{%coupon_userinfo}}','is_disabled');
		$this->createIndex('is_used','{{%coupon_userinfo}}','is_used');

    }

    public function safeDown()
    {
        $this->dropTable('{{%coupon_userinfo}}');

        return true;
    }
}
