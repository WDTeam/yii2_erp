<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_063719_create_table_operation_price_strategy extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'价格策略表\'';
        }
        $this->createTable('{{%operation_price_strategy}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT COMMENT \'编号\'' ,
            'operation_price_strategy_name' => Schema::TYPE_STRING . '(30) DEFAULT NULL COMMENT \'价格策略名称\'',
            'operation_price_strategy_unit' => Schema::TYPE_STRING . '(30) DEFAULT NULL COMMENT \'计量单位名称\'',
            'operation_price_strategy_lowest_consume_unit' => Schema::TYPE_STRING . '(30) DEFAULT NULL COMMENT \'最低消费计量单位\'',
            'created_at' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'编辑时间\'',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%operation_price_strategy}}');

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
