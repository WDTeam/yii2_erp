<?php

use yii\db\Schema;
use yii\db\Migration;

class m150921_114940_create_table_finance_worker_order_income extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'阿姨订单收入记录表\'';
        }
        $this->createTable('{{%finance_worker_order_income}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'主键\'' ,
            'worder_id' => Schema::TYPE_INTEGER . '(10) NOT NULL COMMENT \'阿姨id\'',
            'order_id' => Schema::TYPE_INTEGER . '(10) COMMENT \'订单id\'',
            'finance_worker_order_income_type' => Schema::TYPE_SMALLINT . '(1) NOT NULL COMMENT \'阿姨收入类型，0订单收入（线上支付），1订单收入（现金），2路补，3晚补，4扑空补助,5渠道奖励，6小保养\'',
            'finance_worker_order_income' => Schema::TYPE_DECIMAL . '(10,2)  COMMENT \'阿姨收入\'',
            'order_booked_count' => Schema::TYPE_INTEGER. '(10)  COMMENT \'预约服务数量，即工时\'',
            'isSettled' => Schema::TYPE_SMALLINT. '(1) DEFAULT 0 COMMENT \'是否已结算，0为未结算，1为已结算\'',
            'finance_settle_apply_id' => Schema::TYPE_INTEGER. '(10)  COMMENT \'结算申请Id\'',
            'isdel' => Schema::TYPE_SMALLINT. '(1) DEFAULT 1 COMMENT \'是否被删除，0为启用，1为删除\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11)  COMMENT \'结算时间\'',
            'created_at' => Schema::TYPE_INTEGER. '(11) COMMENT \'创建时间\'',
        ], $tableOptions);
        $this->batchInsert('{{%finance_worker_order_income}}',
            ['id','worder_id','order_id','finance_worker_order_income_type','finance_worker_order_income',
                'order_booked_count','isSettled',
                'isdel','updated_at','created_at'],
            [
                [1,111,'666',1,50,2,0,0,time(),time()],
                [2,111,'666',3,10,0,0,0,time(),time()],
                [3,111,'666',4,10,0,0,0,time(),time()],
                [4,222,'777',1,100,4,0,0,time(),time()],
                [5,222,'777',1,100,4,0,0,time(),time()],
                [6,222,'888',1,100,4,0,0,time(),time()],
                [7,222,'888',1,150,6,0,0,time(),time()],
            ]);
    }

    public function down()
    {
        $this->dropTable('{{%finance_worker_order_income}}');
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
