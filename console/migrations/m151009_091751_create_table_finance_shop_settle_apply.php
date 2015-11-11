<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151009_091751_create_table_finance_shop_settle_apply extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'门店结算申请表\'';
        }
        $this->createTable('{{%finance_shop_settle_apply}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'主键\'' ,
            'finance_shop_settle_apply_code' => Schema::TYPE_STRING . '(32)  COMMENT \'门店结算编号\'' ,
            'shop_id' => Schema::TYPE_INTEGER . '(10) NOT NULL COMMENT \'门店id\'',
            'shop_name' => Schema::TYPE_STRING . '(100) NOT NULL COMMENT \'门店名称\'',
            'shop_manager_id' => Schema::TYPE_INTEGER . '(10) NOT NULL COMMENT \'归属家政id\'',
            'shop_manager_name' => Schema::TYPE_STRING . '(100) NOT NULL COMMENT \'归属家政名称\'',
            'finance_shop_settle_apply_order_count' => Schema::TYPE_INTEGER . '(10) NOT NULL DEFAULT 0  COMMENT \'完成总单量\'',
            'finance_shop_settle_apply_fee_per_order' => Schema::TYPE_DECIMAL . '(10,2) NOT NULL DEFAULT 0  COMMENT \'每单管理费\'',
            'finance_shop_settle_apply_fee' => Schema::TYPE_DECIMAL . '(10,2) DEFAULT 0 COMMENT \'管理费\'',
            'finance_shop_settle_apply_status' => Schema::TYPE_INTEGER . '(2)  DEFAULT 0  COMMENT \'申请结算状态，-4财务确认结算未通过;-3财务审核不通过；-2线下审核不通过；-1门店财务审核不通过；0提出申请，正在门店财务审核；1门店财务审核通过，等待线下审核；2线下审核通过，等待财务审核；3财务审核通过，等待财务确认结算；4财务确认结算；\'',
            'finance_shop_settle_apply_cycle' => Schema::TYPE_INTEGER . '(1) NOT NULL COMMENT \'结算周期，1周结，2月结\'',
            'finance_shop_settle_apply_cycle_des' => Schema::TYPE_TEXT . '(20) NOT NULL COMMENT \'结算周期，周结，月结\'',
            'finance_shop_settle_apply_reviewer' => Schema::TYPE_STRING . '(20)  COMMENT \'审核人姓名\'',
            'finance_shop_settle_apply_starttime' => Schema::TYPE_INTEGER . '(10)  COMMENT \'本次结算开始时间(统计)，例如：2015.9.1 00:00:00对应的int值\'',
            'finance_shop_settle_apply_endtime' => Schema::TYPE_INTEGER . '(10)  COMMENT \'本次结算结束时间(统计)，例如：2015.9.30 23:59:59对应的int值\'',
            'is_softdel' => Schema::TYPE_SMALLINT. '(1) DEFAULT 0 COMMENT \'是否被删除，0为启用，1为删除\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11)  COMMENT \'审核时间\'',
            'created_at' => Schema::TYPE_INTEGER. '(11) COMMENT \'申请时间\'',
            'comment' => Schema::TYPE_TEXT. ' COMMENT \'备注，可能是审核不通过原因\'',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%finance_shop_settle_apply}}');
        return true;
    }
}
