<?php

use yii\db\Schema;
use yii\db\Migration;

class m150921_115002_create_table_finance_worker_non_order_income extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'阿姨非订单收入记录表\'';
        }
        $this->createTable('{{%finance_worker_non_order_income}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'主键\'' ,
            'worker_id' => Schema::TYPE_INTEGER . '(10) NOT NULL COMMENT \'阿姨id\'',
            'finance_worker_non_order_income_id' => Schema::TYPE_SMALLINT . '(1) NOT NULL COMMENT \'阿姨收入id，可能是任务Id，也可能是赔偿Id\'',
            'finance_worker_non_order_income_code' => Schema::TYPE_STRING . '(1) NOT NULL COMMENT \'阿姨收入编号，可能是任务Id，也可能是赔偿编号\'',
            'finance_worker_non_order_income_type' => Schema::TYPE_SMALLINT . '(1) NOT NULL COMMENT \'阿姨收入类型，1,任务；2投诉；3赔偿\'',
            'finance_worker_non_order_income_name' => Schema::TYPE_STRING . '(100) COMMENT \'阿姨收入名称，可能是任务名称，也可能是赔偿名称\'',
            'finance_worker_non_order_income' => Schema::TYPE_DECIMAL . '(10,2)  COMMENT \'阿姨收入\'',
            'finance_worker_non_order_income_des' => Schema::TYPE_TEXT . '  COMMENT \'阿姨收入规则描述\'',
            'finance_worker_non_order_income_complete_time' => Schema::TYPE_INTEGER . '(10)  COMMENT \'收入完成时间\'',
            'finance_worker_non_order_income_starttime' => Schema::TYPE_INTEGER . '(10)  COMMENT \'本次结算开始时间(统计)，例如：2015.9.1 00:00:00对应的int值\'',
            'finance_worker_non_order_income_endtime' => Schema::TYPE_INTEGER . '(10)  COMMENT \'本次结算结束时间(统计)，例如：2015.9.30 23:59:59对应的int值\'',
            'finance_worker_non_order_income_isSettled' => Schema::TYPE_SMALLINT. '(1) DEFAULT 0 COMMENT \'是否已结算，0为未结算，1为已结算\'',
            'finance_worker_settle_apply_id' => Schema::TYPE_INTEGER. '(10)  COMMENT \'结算申请Id\'',
            'is_softdel' => Schema::TYPE_SMALLINT. '(1) DEFAULT 1 COMMENT \'是否被删除，0为启用，1为删除\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11)  COMMENT \'结算时间\'',
            'created_at' => Schema::TYPE_INTEGER. '(11) COMMENT \'创建时间\'',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%finance_worker_non_order_income}}');

        return true;
    }
}
