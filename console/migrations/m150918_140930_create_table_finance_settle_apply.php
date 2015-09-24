<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_140930_create_table_finance_settle_apply extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'结算申请表\'';
        }
        $this->createTable('{{%finance_settle_apply}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'主键\'' ,
            'worder_id' => Schema::TYPE_INTEGER . '(10) NOT NULL COMMENT \'阿姨id\'',
            'worder_tel' => Schema::TYPE_STRING . '(11) NOT NULL COMMENT \'阿姨电话\'',
            'worker_type_id' => Schema::TYPE_INTEGER . '(1) NOT NULL COMMENT \'阿姨类型Id\'',
            'worker_type_name' => Schema::TYPE_STRING . '(30) NOT NULL COMMENT \'阿姨职位类型\'',
            'finance_settle_apply_money' => Schema::TYPE_DECIMAL . '(10,2) NOT NULL COMMENT \'申请结算金额\'',
            'finance_settle_apply_man_hour' => Schema::TYPE_INTEGER . '(10) NOT NULL COMMENT \'订单总工时\'',
            'finance_settle_apply_order_money' => Schema::TYPE_DECIMAL . '(10,2) NOT NULL COMMENT \'工时费\'',
            'finance_settle_apply_order_cash_money' => Schema::TYPE_DECIMAL . '(10,2) DEFAULT 0 COMMENT \'收取现金\'',
            'finance_settle_apply_non_order_money' => Schema::TYPE_DECIMAL . '(10,2) DEFAULT 0 COMMENT \'非订单收入，即帮补费\'',
            'finance_settle_apply_far_subsidy' => Schema::TYPE_DECIMAL . '(10,2)  DEFAULT 0 COMMENT \'路补\'',
            'finance_settle_apply_night_subsidy' => Schema::TYPE_DECIMAL . '(10,2)  DEFAULT 0 COMMENT \'晚补\'',
            'finance_settle_apply_empty_handed_subsidy' => Schema::TYPE_DECIMAL . '(10,2) DEFAULT 0 COMMENT \'扑空补\'',
            'finance_settle_apply_attendance_bonus' => Schema::TYPE_DECIMAL . '(10,2) DEFAULT 0 COMMENT \'全勤奖\'',
            'finance_settle_apply_no_complaint_bonus' => Schema::TYPE_DECIMAL . '(10,2) DEFAULT 0 COMMENT \'无投诉奖\'',
            'finance_settle_apply_daily_violation_bonus' => Schema::TYPE_DECIMAL . '(10,2) DEFAULT 0 COMMENT \'日常违规扣款\'',
            'finance_settle_apply_complaint_reduction' => Schema::TYPE_DECIMAL . '(10,2)  DEFAULT 0 COMMENT \'投诉扣款\'',
            'finance_settle_apply_compensate_reduction' => Schema::TYPE_DECIMAL . '(10,2) DEFAULT 0 COMMENT \'赔偿扣款\'',
            'finance_settle_apply_task_bonus' => Schema::TYPE_DECIMAL . '(10,2)  DEFAULT 0 COMMENT \'阿姨任务奖励\'',
            'finance_settle_apply_small_maintain' => Schema::TYPE_DECIMAL . '(10,2) DEFAULT 0  COMMENT \'小保养\'',
            'finance_settle_apply_channel_bonus' => Schema::TYPE_DECIMAL . '(10,2)  DEFAULT 0 COMMENT \'渠道奖励\'',
            'finance_settle_apply_status' => Schema::TYPE_INTEGER . '(2) NOT NULL COMMENT \'申请结算状态，-4财务确认结算未通过;-3财务审核不通过；-2线下审核不通过；-1门店财务审核不通过；0提出申请，正在门店财务审核；1门店财务审核通过，等待线下审核；2线下审核通过，等待财务审核；3财务审核通过，等待财务确认结算；4财务确认结算；\'',
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
            ['id','worder_id','worder_tel','worker_type_id','worker_type_name','finance_settle_apply_money','finance_settle_apply_man_hour',
                'finance_settle_apply_order_money','finance_settle_apply_order_cash_money','finance_settle_apply_non_order_money',
                'finance_settle_apply_status','finance_settle_apply_cycle','finance_settle_apply_cycle_des','finance_settle_apply_reviewer',
                    'finance_settle_apply_far_subsidy','finance_settle_apply_night_subsidy',
                    'finance_settle_apply_empty_handed_subsidy','finance_settle_apply_attendance_bonus',
                    'finance_settle_apply_no_complaint_bonus','finance_settle_apply_daily_violation_bonus',
                    'finance_settle_apply_complaint_reduction','finance_settle_apply_compensate_reduction',
                    'finance_settle_apply_task_bonus','finance_settle_apply_small_maintain',
                    'finance_settle_apply_channel_bonus',
                'finance_settle_apply_starttime','finance_settle_apply_endtime',
                'isdel','updated_at','created_at'],
            [
                [1,111,'13888888888',2,'全职',220,6,150,0,70,0,2,'月结','魏北南',
                    10,10,
                    0,50,
                    0,0,
                    0,0,
                    0,0,
                    0,
                    date("Y-m-d",strtotime("-1 month")),time(),0,time(),time()],
                [2,222,'13899999999',1,'兼职',200,8,200,0,0,0,1,'周结','潘高峰',
                    0,0,
                    0,0,
                    0,0,
                    0,0,
                    0,0,
                    0,
                    date("Y-m-d",strtotime("-1 week")),time(),0,time(),time()],
                [3,333,'13899999999',1,'兼职',250,10,250,0,0,0,1,'周结','李胜强',
                    0,0,
                    0,0,
                    0,0,
                    0,0,
                    0,0,
                    0,
                    time(),date("Y-m-d",strtotime("-1 week")),0,time(),time()],
            ]);
    }

    public function down()
    {
        $this->dropTable('{{%finance_settle_apply}}');
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
