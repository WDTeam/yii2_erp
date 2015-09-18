<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_123012_create_table_order extends Migration
{

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions ='CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'订单表\'';
        }

        
        $this->createTable('{{%order}}', [
            'id'=> Schema::TYPE_PK .' NOT NULL AUTO_INCREMENT COMMENT \'编号\'',
            'order_parent_id' => Schema::TYPE_INTEGER.'(10) unsigned DEFAULT 0 COMMENT \'父级id\'',
            'order_is_parent' => Schema::TYPE_SMALLINT . '(1) unsigned DEFAULT 0 COMMENT \'有无子订单\'',
            'created_at' => Schema::TYPE_INTEGER.'(10) unsigned DEFAULT 0 COMMENT \'创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER.'(10) unsigned DEFAULT 0 COMMENT \'修改时间\'',
            'order_status' => Schema::TYPE_SMALLINT . '(4) unsigned DEFAULT 0 COMMENT \'订单状态\'',
            'order_service_type' => Schema::TYPE_SMALLINT . '(4) unsigned DEFAULT 0 COMMENT \'订单服务类别\'',
            'order_src' => Schema::TYPE_SMALLINT . '(4) unsigned DEFAULT 0 COMMENT \'订单来源\'',
            'channel_id' => Schema::TYPE_INTEGER.'(10) unsigned DEFAULT 0 COMMENT \'下单渠道\'',
            'channel_oid' => Schema::TYPE_STRING . '(255) DEFAULT NULL COMMENT \'渠道订单编号\'',
            'user_id' => Schema::TYPE_INTEGER.'(10) unsigned DEFAULT 0 COMMENT \'用户编号\'',
            'order_user_phone' => Schema::TYPE_STRING .'(30) NOT NULL DEFAULT \'\' COMMENT \'用户手机号\'',
            'order_booked_begin_time' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'预约开始时间\'',
            'order_booked_end_time' => Schema::TYPE_INTEGER.'(10) unsigned DEFAULT 0 COMMENT \'预约结束时间\'',
            'order_booked_count' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'预约服务数量\'',
            'address_id' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'地址\'',
            'order_money' => Schema::TYPE_DECIMAL . '(8,0) NOT NULL DEFAULT 0 COMMENT \'订单金额\'',
            'order_booked_worker_id' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'指定阿姨\'',
            'order_user_need' => Schema::TYPE_STRING . '(255) DEFAULT 0 COMMENT \'用户需求\'',
            'order_user_memo' => Schema::TYPE_STRING . '(255) DEFAULT 0 COMMENT \'用户备注\'',
            'order_400_memo' => Schema::TYPE_STRING . '(255) DEFAULT 0 COMMENT \'客服备注\'',
            'pay_type_id' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'支付方式\'',
            'pay_channel_id' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'支付渠道id\'',
            'order_pay_num' => Schema::TYPE_STRING . '(255) DEFAULT NULL COMMENT \'支付流水号\'',
            'order_balance' => Schema::TYPE_DECIMAL . '(8,2) unsigned NOT NULL DEFAULT 0 COMMENT \'使用余额\'',
            'order_card_money' => Schema::TYPE_DECIMAL . '(8,2) unsigned NOT NULL DEFAULT 0 COMMENT \'服务卡金额\'',
            'coupon_id' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'优惠券id\'',
            'order_coupon_money' => Schema::TYPE_DECIMAL . '(8,2) NOT NULL DEFAULT 0 COMMENT \'优惠卷金额\'',
            'discount_id' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'促销id\'',
            'order_discount_money' => Schema::TYPE_DECIMAL . '(8,2) unsigned NOT NULL DEFAULT 0 COMMENT \'促销金额\'',
            'order_real_money' => Schema::TYPE_DECIMAL . '(8,2) unsigned NOT NULL DEFAULT 0 COMMENT \'实收金额\'',
            'worker_id' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'阿姨id\'',
            'worker_type_id' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'阿姨职位类型？阿姨结完单才能转职\'',
            'worker_bind_type_id' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'接单方式 阿姨接单 小家政 客服\'',
            'order_lock' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'是否锁定 1锁定 0未锁定\'',
            'comment_id' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'评价id\'',
            'order_worker_bonus' => Schema::TYPE_TEXT . ' NOT NULL COMMENT \'补贴明细\'',
            'order_worker_bonus_money' => Schema::TYPE_DECIMAL . '(8,2) unsigned NOT NULL DEFAULT 0 COMMENT \'补贴金额\'',
            'order_pop_pay_money' => Schema::TYPE_DECIMAL . '(8,2) unsigned NOT NULL DEFAULT 0 COMMENT \'合作方结算金额\'',
            'invoice_id' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'发票id\'',
            'compare_id' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'对账id\'',
            'shop_id' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'门店id\'',
            'admin_id' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'操作人id\'',
            'isdel' => Schema::TYPE_SMALLINT . '(1) unsigned NOT NULL DEFAULT 0 COMMENT \'是否已删除\'',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%order}}');
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
