<?php

use yii\db\Schema;
use yii\db\Migration;

class m151007_153202_create_table_order extends Migration
{

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions ='CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'订单表\'';
        }


        $this->createTable('{{%order}}', [
            'id'=> Schema::TYPE_BIGPK .' NOT NULL AUTO_INCREMENT COMMENT \'编号\'',
            'order_code' => Schema::TYPE_STRING.'(64) NOT NULL DEFAULT \'\' COMMENT \'订单号\'',
            'order_batch_code' => Schema::TYPE_STRING.'(64) DEFAULT \'\' COMMENT \'周期订单号\'',
            'order_parent_id' => Schema::TYPE_BIGINT.'(20) unsigned NOT NULL DEFAULT 0 COMMENT \'父级id\'',
            'order_is_parent' => Schema::TYPE_BOOLEAN . '(1) unsigned NOT NULL DEFAULT 0 COMMENT \'有无子订单 1有 0无\'',
            'created_at' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'修改时间\'',
            'isdel' => Schema::TYPE_BOOLEAN . '(1) unsigned NOT NULL DEFAULT 0 COMMENT \'是否已删除\'',
            'ver' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 1 COMMENT \'乐观锁\'',
            'version' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 1 COMMENT \'标记\'',

//===============================下单信息
            'order_ip' => Schema::TYPE_STRING.'(128) NOT NULL DEFAULT \'\' COMMENT \'下单IP\'',
            'order_service_type_id' => Schema::TYPE_INTEGER . '(11) unsigned NOT NULL DEFAULT 0 COMMENT \'订单服务类别ID\'',
            'order_service_type_name' => Schema::TYPE_STRING . '(128) NOT NULL DEFAULT \'\' COMMENT \'订单服务类别\'',
            'order_service_item_id' => Schema::TYPE_INTEGER . '(11) unsigned NOT NULL DEFAULT 0 COMMENT \'订单服务项ID\'',
            'order_service_item_name' => Schema::TYPE_STRING . '(128) NOT NULL DEFAULT \'\' COMMENT \'订单服务项\'',
            'order_src_id' => Schema::TYPE_SMALLINT . '(4) unsigned DEFAULT 0 COMMENT \'订单来源，订单入口id\'',
            'order_src_name' => Schema::TYPE_STRING . '(128)  DEFAULT \'\' COMMENT \'订单来源，订单入口名称\'',
            'channel_id' => Schema::TYPE_INTEGER.'(10) unsigned DEFAULT 0 COMMENT \'订单渠道ID\'',
            'order_channel_name' => Schema::TYPE_STRING.'(64)  DEFAULT \'\' COMMENT \'订单渠道名称\'',
//===============================服务信息
            'order_unit_money'=>Schema::TYPE_DECIMAL . '(8,2) NOT NULL DEFAULT 0 COMMENT \'订单单位价格\'',
            'order_money' => Schema::TYPE_DECIMAL . '(8,2) NOT NULL DEFAULT 0 COMMENT \'订单金额\'',
            'order_booked_count' => Schema::TYPE_DECIMAL.'(8.1) NOT NULL DEFAULT  \'0.0\' COMMENT \'预约服务数量（时长）\'',
            'order_booked_begin_time' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'预约开始时间\'',
            'order_booked_end_time' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'预约结束时间\'',
            'city_id' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'城市ID\'',
            'district_id' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'商圈ID\'',
            'address_id' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'地址ID\'',
            'order_address'=>Schema::TYPE_STRING.'(255) NOT NULL DEFAULT \'\' COMMENT \'详细地址 包括 联系人 手机号\'',
            'order_lat' => Schema::TYPE_DOUBLE.' DEFAULT NULL COMMENT \'纬度\'',
            'order_lng' => Schema::TYPE_DOUBLE.' DEFAULT NULL COMMENT \'经度\'',
            'order_booked_worker_id' => Schema::TYPE_INTEGER.'(10) unsigned DEFAULT 0 COMMENT \'指定阿姨\'',

//===========================对账信息
            'checking_id' => Schema::TYPE_INTEGER.'(10) unsigned  DEFAULT 0 COMMENT \'对账id\'',
//===========================其他信息
            'order_cs_memo' => Schema::TYPE_STRING . '(255) DEFAULT \'\' COMMENT \'客服备注\'',
            'order_sys_memo' => Schema::TYPE_STRING . '(255) DEFAULT \'\' COMMENT \'系统备注\'',
            'order_cancel_cause_id' => Schema::TYPE_INTEGER.'(11) unsigned  DEFAULT 0 COMMENT \'取消具体原因id\'',
            'order_cancel_cause_detail' => Schema::TYPE_STRING . '(255) DEFAULT \'\' COMMENT \'取消具体原因\'',
            'order_cancel_cause_memo' => Schema::TYPE_STRING . '(255) DEFAULT \'\' COMMENT \'取消原因备注\'',

        ], $tableOptions);

        $this->createIndex('idx-order-order_code', '{{%order}}', 'order_code',true);
        $this->createIndex('idx-order-order_parent_id', '{{%order}}', 'order_parent_id');
        $this->createIndex('idx-order-isdel', '{{%order}}', 'isdel');
    }

    public function down()
    {
        $this->dropTable('{{%order}}');

        return true;
    }


}
