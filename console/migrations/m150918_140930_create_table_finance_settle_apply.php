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
            'worker_id' => Schema::TYPE_INTEGER . '(10) NOT NULL COMMENT \'阿姨id\'',
            'worker_name' => Schema::TYPE_STRING . '(30) NOT NULL COMMENT \'阿姨姓名\'',
            'worker_tel' => Schema::TYPE_STRING . '(11) NOT NULL COMMENT \'阿姨电话\'',
            'worker_type_id' => Schema::TYPE_INTEGER . '(2) NOT NULL COMMENT \'阿姨类型Id,1自营 2非自营\'',
            'worker_type_name' => Schema::TYPE_STRING . '(30) NOT NULL COMMENT \'阿姨职位描述\'',
            'worker_identity_id' => Schema::TYPE_INTEGER . '(2) NOT NULL COMMENT \'阿姨身份Id,1全职 2兼职 3高峰 4时段 \'',
            'worker_identity_name' => Schema::TYPE_STRING . '(30) NOT NULL COMMENT \'阿姨身份描述\'',
            'shop_id' => Schema::TYPE_INTEGER . '(10) DEFAULT NULL COMMENT \'门店id\'',
            'shop_name' => Schema::TYPE_STRING . '(100) DEFAULT NULL COMMENT \'门店名称\'',
            'shop_manager_id' => Schema::TYPE_INTEGER . '(10) DEFAULT NULL COMMENT \'归属家政id\'',
            'shop_manager_name' => Schema::TYPE_STRING . '(100) DEFAULT NULL COMMENT \'归属家政名称\'',
            'finance_settle_apply_order_count' => Schema::TYPE_INTEGER . '(10) NOT NULL DEFAULT 0  COMMENT \'总单量\'',
            'finance_settle_apply_man_hour' => Schema::TYPE_INTEGER . '(10) NOT NULL DEFAULT 0  COMMENT \'订单总工时\'',
            'finance_settle_apply_order_money' => Schema::TYPE_DECIMAL . '(10,2) NOT NULL DEFAULT 0  COMMENT \'工时费小计\'',
            'finance_settle_apply_task_count' => Schema::TYPE_INTEGER . '(10) NOT NULL DEFAULT 0  COMMENT \'完成任务数\'',
            'finance_settle_apply_task_money' => Schema::TYPE_DECIMAL . '(10,2) NOT NULL DEFAULT 0  COMMENT \'完成任务奖励\'',
            'finance_settle_apply_base_salary' => Schema::TYPE_DECIMAL . '(10,2) NOT NULL DEFAULT 0  COMMENT \'底薪\'',
            'finance_settle_apply_base_salary_subsidy' => Schema::TYPE_DECIMAL . '(10,2) NOT NULL DEFAULT 0  COMMENT \'底薪补贴\'',
            'finance_settle_apply_money_except_deduct_cash' => Schema::TYPE_DECIMAL . '(10,2) NOT NULL DEFAULT 0  COMMENT \'应结合计,没有减除扣款和现金\'',
            'finance_settle_apply_money_deduction' => Schema::TYPE_DECIMAL . '(10,2) NOT NULL DEFAULT 0  COMMENT \'扣款小计\'',
            'finance_settle_apply_money_except_cash' => Schema::TYPE_DECIMAL . '(10,2) NOT NULL DEFAULT 0  COMMENT \'本次应结合计，没有减除现金\'',
            'finance_settle_apply_order_cash_count' => Schema::TYPE_INTEGER . '(10) NOT NULL DEFAULT 0  COMMENT \'现金订单数\'',
            'finance_settle_apply_order_cash_money' => Schema::TYPE_DECIMAL . '(10,2) DEFAULT 0 COMMENT \'收取现金\'',
            'finance_settle_apply_money' => Schema::TYPE_DECIMAL . '(10,2) NOT NULL  DEFAULT 0  COMMENT \'本次应付合计\'',
            'finance_settle_apply_order_noncash_count' => Schema::TYPE_INTEGER . '(10) NOT NULL DEFAULT 0  COMMENT \'非现金订单\'',
            'finance_settle_apply_order_money_except_cash' => Schema::TYPE_DECIMAL . '(10,2) NOT NULL COMMENT \'工时费应结，扣除了现金\'',
            'finance_settle_apply_status' => Schema::TYPE_INTEGER . '(2) NOT NULL DEFAULT 0  COMMENT \'申请结算状态，-2财务审核不通过；-1业务部门审核不通过；0提出申请；1业务部门审核通过；2财务审核通过；3财务确认打款；\'',
            'finance_settle_apply_cycle' => Schema::TYPE_INTEGER . '(1) NOT NULL COMMENT \'结算周期，1周结，2月结\'',
            'finance_settle_apply_cycle_des' => Schema::TYPE_TEXT . '(20) NOT NULL COMMENT \'结算周期，周结，月结\'',
            'finance_settle_apply_reviewer' => Schema::TYPE_STRING . '(20)  COMMENT \'审核人姓名\'',
            'finance_settle_apply_starttime' => Schema::TYPE_INTEGER . '(10)  COMMENT \'本次结算开始时间(统计)，例如：2015.9.1 00:00:00对应的int值\'',
            'finance_settle_apply_endtime' => Schema::TYPE_INTEGER . '(10)  COMMENT \'本次结算结束时间(统计)，例如：2015.9.30 23:59:59对应的int值\'',
            'isManagementFeeDone' => Schema::TYPE_SMALLINT. '(1) DEFAULT 0 COMMENT \'门店服务管理费是否已结算，0为未结算，1为已结算\'',
            'isWorkerConfirmed' => Schema::TYPE_SMALLINT. '(1) DEFAULT 0 COMMENT \'阿姨是否已经确认结算单，0为未确认，1为已确认\'',
            'isdel' => Schema::TYPE_SMALLINT. '(1) DEFAULT 0 COMMENT \'是否被删除，0为启用，1为删除\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11)  COMMENT \'审核时间\'',
            'created_at' => Schema::TYPE_INTEGER. '(11) COMMENT \'申请时间\'',
             'comment' => Schema::TYPE_TEXT. ' COMMENT \'备注，可能是审核不通过原因\'',
        ], $tableOptions);
        $this->batchInsert('{{%finance_settle_apply}}',
            ['id','worker_id','worker_name','worker_tel','worker_type_id','worker_type_name','worker_identity_id','worker_identity_name',
                'shop_id','shop_name','shop_manager_id','shop_manager_name',
                'finance_settle_apply_order_count','finance_settle_apply_man_hour','finance_settle_apply_order_money','finance_settle_apply_task_count',
                'finance_settle_apply_task_money','finance_settle_apply_base_salary','finance_settle_apply_base_salary_subsidy','finance_settle_apply_money_except_deduct_cash',
                'finance_settle_apply_money_deduction','finance_settle_apply_money_except_cash','finance_settle_apply_order_cash_count','finance_settle_apply_order_cash_money',
		'finance_settle_apply_money','finance_settle_apply_order_noncash_count','finance_settle_apply_order_money_except_cash',
                'finance_settle_apply_status','finance_settle_apply_cycle','finance_settle_apply_cycle_des','finance_settle_apply_reviewer',
                'finance_settle_apply_starttime','finance_settle_apply_endtime',
                'isdel','updated_at','created_at'],
            [
                [1,111,'刘阿姨','13888888888',1,'自营',1,'全职',
                    1,'望京门店',0,'e家洁',
                    3,6,150,0,
                    0,100,0,150,
                    0,150,0,0,
                    150,6,150,
                    0,2,'月结','刘阿姨',
                    strtotime(date('Y-m-01 00:00:00', strtotime('2015-09'))),strtotime(date('Y-m-t 23:59:59', strtotime('2015-09'))),0,time(),time()],
                [221,222,'卫阿姨','13888888886',1,'自营',2,'兼职',
                    1,'望京门店',0,'e家洁',
                    3,6,150,0,
                    0,100,0,150,
                    0,150,0,0,
                    150,6,150,
                    0,1,'周结','',
                    strtotime(date('Y-m-01 00:00:00', strtotime('2015-09'))),strtotime(date('Y-m-t 23:59:59', strtotime('2015-09'))),0,time(),time()],
                [338,333,'陶阿姨','13899999999',2,'非自营',2,'兼职',
                    1,'望京门店',1,'武大家政',
                    3,6,150,0,
                    0,100,0,150,
                    0,150,0,0,
                    150,6,150,
                    0,1,'周结','',
                    strtotime(date('Y-m-01 00:00:00', strtotime('2015-09'))),strtotime(date('Y-m-t 23:59:59', strtotime('2015-09'))),0,time(),time()],
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
