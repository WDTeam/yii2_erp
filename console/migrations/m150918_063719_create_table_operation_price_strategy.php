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
            'operation_category_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'对应服务品类编号（所属分类编号冗余）\'',
            'operation_category_ids' => Schema::TYPE_STRING . '(255) DEFAULT NULL COMMENT \'对应服务品类的所有编号以“,”关联\'',
            'operation_category_name' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'对应服务品类名称（所属分类名称冗余）\'',
            'operation_price_strategy_name' => Schema::TYPE_STRING . '(30) DEFAULT NULL COMMENT \'价格策略名称\'',
            'operation_price_strategy_unit' => Schema::TYPE_STRING . '(30) DEFAULT NULL COMMENT \'计量单位名称\'',
            'operation_price_strategy_lowest_consume_unit' => Schema::TYPE_STRING . '(30) DEFAULT NULL COMMENT \'最低消费计量单位\'',
            'is_softdel' => Schema::TYPE_SMALLINT . '(1) unsigned NOT NULL DEFAULT 0 COMMENT \'状态\'',
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
