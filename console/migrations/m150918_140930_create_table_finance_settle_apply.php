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
            'finance_settle_apply_order_cash_money' => Schema::TYPE_DECIMAL . '(10,2) NOT NULL COMMENT \'收取的现金\'',
            'finance_settle_apply_non_order_money' => Schema::TYPE_DECIMAL . '(10,2) NOT NULL COMMENT \'非订单收入，即帮补费\'',
            'finance_settle_apply_status' => Schema::TYPE_INTEGER . '(2) NOT NULL COMMENT \'申请结算状态，-3财务打款失败；-2财务审核不通过；-1线下审核不通过；0提出申请，正在线下审核；1线下审核通过，等待财务审核；2财务审核通过，等待财务打款；3财务打款成功，申请完结；\'',
            'finance_settle_apply_cycle' => Schema::TYPE_INTEGER . '(1) NOT NULL COMMENT \'结算周期，1周结，2月结\'',
            'finance_settle_apply_reviewer' => Schema::TYPE_STRING . '(20)  COMMENT \'审核人姓名\'',
            'isdel' => Schema::TYPE_SMALLINT. '(1) DEFAULT 0 COMMENT \'是否被删除，0为启用，1为删除\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11)  COMMENT \'审核时间\'',
            'created_at' => Schema::TYPE_INTEGER. '(11) COMMENT \'申请时间\'',
        ], $tableOptions);
        $this->batchInsert('{{%finance_settle_apply}}',
            ['id','worder_id','worder_tel','worker_type_id','worker_type_name','finance_settle_apply_money','finance_settle_apply_man_hour',
                'finance_settle_apply_order_money','finance_settle_apply_order_cash_money','finance_settle_apply_non_order_money','finance_settle_apply_status','finance_settle_apply_cycle','finance_settle_apply_reviewer',
                'isdel','updated_at','created_at'],
            [
                [1,111,'13888888888',1,'兼职',400,6,300,0,100,0,1,'魏北南',0,time(),time()],
                [2,222,'13899999999',1,'全职',500,8,300,0,0,0,1,'潘高峰',0,time(),time()],
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
