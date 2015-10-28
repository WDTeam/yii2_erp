<?php

use yii\db\Schema;
use yii\db\Migration;

class m150919_102729_create_table_coupon extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'优惠规则表\'';
        }
        $this->createTable('{{%coupon}}', [
                'id' => Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'主键\'',

		'coupon_name'=>  Schema::TYPE_STRING. '(255) DEFAULT NULL COMMENT \'优惠券名称\'',
		'coupon_price'=>  Schema::TYPE_DECIMAL. '(8,2) DEFAULT 0 COMMENT \'优惠券价值\'',
		
		'coupon_type'=>  Schema::TYPE_SMALLINT . '(4) DEFAULT 0 COMMENT \'优惠券类型0为全网优惠券1为类别优惠券2为商品优惠券\'',
		'coupon_type_name'=>  Schema::TYPE_STRING . '(255) DEFAULT NULL COMMENT \'优惠券类型名称\'',

		'coupon_service_type_id'=>  Schema::TYPE_SMALLINT . '(4) DEFAULT NULL COMMENT \'服务类别id\'',
		'coupon_service_type_name'=>  Schema::TYPE_STRING . '(255) DEFAULT NULL COMMENT \'服务类别名称\'',

		'coupon_service_id'=>  Schema::TYPE_SMALLINT . '(4) DEFAULT NULL COMMENT \'服务id\'',
		'coupon_service_name'=>  Schema::TYPE_STRING . '(255) DEFAULT NULL COMMENT \'服务名称\'',

		'coupon_city_limit'=>  Schema::TYPE_SMALLINT . '(4) DEFAULT 0 COMMENT \'城市限制0为不限1为单一城市限制\'',
		'coupon_city_id'=>  Schema::TYPE_INTEGER . '(16) DEFAULT 0 COMMENT \'关联城市\'',
		'coupon_city_name'=>  Schema::TYPE_STRING . '(255) DEFAULT NULL COMMENT \'城市名称\'',

		'coupon_customer_type'=>  Schema::TYPE_SMALLINT . '(4) DEFAULT NULL COMMENT \'适用客户类别逗号分割0为所有用户1为新用户2为老用户3会员4为非会员\'',
		'coupon_customer_type_name'=>  Schema::TYPE_STRING . '(255) DEFAULT NULL COMMENT \'适用客户类别名称\'',
		
		'coupon_time_type'=>  Schema::TYPE_SMALLINT . '(4) DEFAULT 0 COMMENT \'优惠券有效时间类型0为有效领取时间和有效使用时间一致1为过期时间从领取时间开始计算\'',
		'coupon_time_type_name'=>  Schema::TYPE_STRING . '(255) DEFAULT NULL COMMENT \'优惠券有效时间类型名称\'',
		'coupon_begin_at'=>  Schema::TYPE_INTEGER . '(11) DEFAULT 0 COMMENT \'开始时间\'',
		'coupon_end_at'=>  Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'领取时间和使用时间一致时的结束时间\'',
		'coupon_get_end_at'=>  Schema::TYPE_INTEGER . '(11) DEFAULT 0 COMMENT \'领取时间和使用时间不一致时的领取结束时间\'',
		'coupon_use_end_days'=>  Schema::TYPE_SMALLINT . '(4) DEFAULT NULL COMMENT \'领取时间和使用时间不一致时过期天数\'',
		
		'coupon_promote_type'=>  Schema::TYPE_SMALLINT . '(4) DEFAULT 0 COMMENT \'优惠券优惠类型0为立减1为满减2为每减\'',
		'coupon_promote_type_name'=>  Schema::TYPE_STRING . '(255) DEFAULT NULL COMMENT \'优惠券优惠类型名称\'',
		'coupon_order_min_price'=>  Schema::TYPE_DECIMAL. '(8,2) DEFAULT 0 COMMENT \'满减或每减时订单最小金额\'',

		'coupon_code_num'=>  Schema::TYPE_INTEGER . '(8) DEFAULT 0 COMMENT \'优惠码个数\'',
		'coupon_code_max_customer_num'=>  Schema::TYPE_INTEGER . '(8) DEFAULT 0 COMMENT \'单个优惠码最大使用人数\'',

		'is_disabled'=> Schema::TYPE_SMALLINT . '(4) DEFAULT 0 COMMENT \'是否禁用\'',
	        'created_at'=>  Schema::TYPE_INTEGER . '(11) DEFAULT 0 COMMENT \'创建时间\'',
	        'updated_at'=> Schema::TYPE_INTEGER . '(11) DEFAULT 0 COMMENT \'更新时间\'',
	        'is_del'=> Schema::TYPE_SMALLINT . '(4) DEFAULT 0 COMMENT \'是否逻辑删除\'',
		'system_user_id'=>  Schema::TYPE_INTEGER . '(11) DEFAULT 0 COMMENT \'系统用户id\'',
	        'system_user_name'=> Schema::TYPE_STRING . '(255) DEFAULT NULL COMMENT \'系统用户名称\'',
		
	    ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%coupon}}');

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
