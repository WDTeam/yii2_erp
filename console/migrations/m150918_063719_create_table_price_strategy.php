<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_063719_create_table_price_strategy extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT=\'价格策略表\'';
        }
        $this->createTable('{{%price_strategy}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT COMMENT \'编号\'' ,
            'price_strategy_name' => Schema::TYPE_STRING . '(30) DEFAULT NULL COMMENT \'价格策略名称\'',
            'price_strategy_unit' => Schema::TYPE_SMALLINT . '(1) DEFAULT NULL COMMENT \'计量单位名称\'',
            'price_strategy_lowest_consume_unit' => Schema::TYPE_SMALLINT . '(1) DEFAULT NULL COMMENT \'最低消费计量单位\'',
            'create_time' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'创建时间\'',
            'updatetime' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'编辑时间\'',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%price_strategy}}');
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
