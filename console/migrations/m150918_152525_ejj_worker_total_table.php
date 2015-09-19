<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_152525_ejj_worker_total_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'阿姨统计表\'';
        }
        $this->createTable('ejj_worker_total', [
            'worker_id' => Schema::TYPE_PK . '(11)  COMMENT \'主表阿姨id\'',
            'worker_total_order_num' => Schema::TYPE_SMALLINT . '(6) DEFAULT NULL COMMENT \'阿姨订单总数\'',
            'worker_total_order_money' => Schema::TYPE_DECIMAL  . '(16) DEFAULT NULL COMMENT \'阿姨订单总金额\'',
            'worker_total_order_refuse' => Schema::TYPE_SMALLINT. '(6) DEFAULT NULL COMMENT \'阿姨拒绝订单数\'',
            'worker_total_order_complaint' => Schema::TYPE_SMALLINT . '(6) DEFAULT NULL COMMENT \'阿姨接到投诉数\'',
            'worker_total_sale_card' => Schema::TYPE_SMALLINT . '(6) DEFAULT NULL COMMENT \'阿姨销售会员卡数量\'',
            'update_time' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'最后更新时间\'',
        ], $tableOptions);
    }

    public function down()
    {
        echo "m150918_152525_ejj_worker_total_table cannot be reverted.\n";

        return false;
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
