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
            'worder_id' => Schema::TYPE_INTEGER . '(10) NOT NULL COMMENT \'阿姨id\'',
            'finance_worker_non_order_income_type' => Schema::TYPE_SMALLINT . '(1) NOT NULL COMMENT \'阿姨收入类型，1办卡提成，2推荐服务提成，3全勤奖，4无投诉奖，5日常违规扣款，6投诉处罚扣款，7赔偿扣款,8阿姨任务奖励,9小保养\'',
            'finance_worker_non_order_income_des' => Schema::TYPE_TEXT . '(1)  COMMENT \'阿姨收入描述\'',
            'finance_worker_non_order_income_duration' => Schema::TYPE_STRING . '(20)  COMMENT \'阿姨收入的时段，例如：2015年10月份，则为201510\'',
            'finance_worker_non_order_income_isSettled' => Schema::TYPE_SMALLINT. '(1) DEFAULT 0 COMMENT \'是否已结算，0为未结算，1为已结算\'',
            'isdel' => Schema::TYPE_SMALLINT. '(1) DEFAULT 1 COMMENT \'是否被删除，0为启用，1为删除\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11)  COMMENT \'结算时间\'',
            'created_at' => Schema::TYPE_INTEGER. '(11) COMMENT \'创建时间\'',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%finance_worker_non_order_income}}');
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