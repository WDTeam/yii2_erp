<?php

use yii\db\Schema;
use yii\db\Migration;

class m150919_083744_create_table_worker_stat extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'阿姨统计表\'';
        }
        $this->createTable('{{%worker_stat}}', [
            'worker_id' => Schema::TYPE_PK . '(11)  COMMENT \'主表阿姨id\'',
            'worker_stat_order_num' => Schema::TYPE_SMALLINT . '(6) DEFAULT NULL COMMENT \'阿姨订单总数\'',
            'worker_stat_order_money' => Schema::TYPE_DECIMAL  . '(16) DEFAULT NULL COMMENT \'阿姨订单总金额\'',
            'worker_stat_order_refuse' => Schema::TYPE_SMALLINT. '(6) DEFAULT NULL COMMENT \'阿姨拒绝订单数\'',
            'worker_stat_order_complaint' => Schema::TYPE_SMALLINT . '(6) DEFAULT NULL COMMENT \'阿姨接到投诉数\'',
            'worker_stat_sale_cards' => Schema::TYPE_SMALLINT . '(6) DEFAULT NULL COMMENT \'阿姨销售会员卡数量\'',
            'updated_ad' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'最后更新时间\'',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%worker_stat}}');

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
