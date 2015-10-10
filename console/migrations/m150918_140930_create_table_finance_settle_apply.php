<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_140930_create_table_finance_settle_apply extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'阿姨结算申请表\'';
        }
        $this->createTable('{{%finance_settle_apply}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'主键\'' ,
            'worder_id' => Schema::TYPE_INTEGER . '(10) NOT NULL COMMENT \'阿姨id\'',
            'worder_tel' => Schema::TYPE_STRING . '(11) NOT NULL COMMENT \'阿姨电话\'',
            'worker_type_id' => Schema::TYPE_INTEGER . '(1) NOT NULL COMMENT \'阿姨类型Id\'',
            'worker_type_name' => Schema::TYPE_STRING . '(30) NOT NULL COMMENT \'阿姨职位类型\'',
            'shop_id' => Schema::TYPE_INTEGER . '(10) NOT NULL COMMENT \'门店id\'',
            'shop_name' => Schema::TYPE_STRING . '(100) NOT NULL COMMENT \'门店名称\'',
            'shop_manager_id' => Schema::TYPE_INTEGER . '(10) NOT NULL COMMENT \'归属家政id\'',
            'shop_manager_name' => Schema::TYPE_STRING . '(100) NOT NULL COMMENT \'归属家政名称\'',
            'finance_settle_apply_man_hour' => Schema::TYPE_INTEGER . '(10) NOT NULL DEFAULT 0  COMMENT \'订单总工时\'',
            'finance_settle_apply_order_money' => Schema::TYPE_DECIMAL . '(10,2) NOT NULL DEFAULT 0  COMMENT \'工时费\'',
            'finance_settle_apply_order_cash_money' => Schema::TYPE_DECIMAL . '(10,2) DEFAULT 0 COMMENT \'收取现金\'',
            'finance_settle_apply_order_money_except_cash' => Schema::TYPE_DECIMAL . '(10,2) NOT NULL COMMENT \'工时费应结\'',
            'finance_settle_apply_subsidy' => Schema::TYPE_DECIMAL . '(10,2) DEFAULT 0 COMMENT \'总补助费\'',
             'finance_settle_apply_money' => Schema::TYPE_DECIMAL . '(10,2) NOT NULL  DEFAULT 0  COMMENT \'应结算金额\'',
            'finance_settle_apply_status' => Schema::TYPE_INTEGER . '(2) NOT NULL DEFAULT 0  COMMENT \'申请结算状态，-4财务确认结算未通过;-3财务审核不通过；-2线下审核不通过；-1门店财务审核不通过；0提出申请，正在门店财务审核；1门店财务审核通过，等待线下审核；2线下审核通过，等待财务审核；3财务审核通过，等待财务确认结算；4财务确认结算；\'',
            'finance_settle_apply_cycle' => Schema::TYPE_INTEGER . '(1) NOT NULL COMMENT \'结算周期，1周结，2月结\'',
            'finance_settle_apply_cycle_des' => Schema::TYPE_TEXT . '(20) NOT NULL COMMENT \'结算周期，周结，月结\'',
            'finance_settle_apply_reviewer' => Schema::TYPE_STRING . '(20)  COMMENT \'审核人姓名\'',
            'finance_settle_apply_starttime' => Schema::TYPE_INTEGER . '(10)  COMMENT \'本次结算开始时间(统计)，例如：2015.9.1 00:00:00对应的int值\'',
            'finance_settle_apply_endtime' => Schema::TYPE_INTEGER . '(10)  COMMENT \'本次结算结束时间(统计)，例如：2015.9.30 23:59:59对应的int值\'',
            'isdel' => Schema::TYPE_SMALLINT. '(1) DEFAULT 0 COMMENT \'是否被删除，0为启用，1为删除\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11)  COMMENT \'审核时间\'',
            'created_at' => Schema::TYPE_INTEGER. '(11) COMMENT \'申请时间\'',
        ], $tableOptions);
        $this->batchInsert('{{%finance_settle_apply}}',
            ['id','worder_id','worder_tel','worker_type_id','worker_type_name','finance_settle_apply_man_hour',
                'finance_settle_apply_order_money','finance_settle_apply_order_cash_money','finance_settle_apply_order_money_except_cash',
				'finance_settle_apply_subsidy','finance_settle_apply_money',
                'finance_settle_apply_status','finance_settle_apply_cycle','finance_settle_apply_cycle_des','finance_settle_apply_reviewer',

                'finance_settle_apply_starttime','finance_settle_apply_endtime',
                'isdel','updated_at','created_at'],
            [
                [1,111,'13888888888',2,'全职',6,150,0,150,70,220,0,2,'月结','魏北南',

                    strtotime(date('Y-m-01 00:00:00', strtotime('2015-09'))),strtotime(date('Y-m-t 23:59:59', strtotime('2015-09'))),0,time(),time()],
                [2,222,'13899999999',1,'兼职',8,200,0,200,0,200,0,1,'周结','潘高峰',

                    strtotime("-2 month"),strtotime("-1 month"),0,time(),time()],
                [3,333,'13899999999',1,'兼职',10,250,0,250,0,250,0,1,'周结','李胜强',

                    strtotime("-2 month"),strtotime("-1 month"),0,time(),time()],
            ]);
    }

    public function down()
    {
        $this->dropTable('{{%finance_settle_apply}}');

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
