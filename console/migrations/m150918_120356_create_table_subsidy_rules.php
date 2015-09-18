<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_120356_create_table_subsidy_rules extends Migration
{
    public function up()
    {
         $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'阿姨补助或奖励规则表\'';
        }
        $this->createTable('{{%subsidy_rules}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'主键\'' ,
            'subsidy_category_id' => Schema::TYPE_INTEGER . '(10) NOT NULL COMMENT \'补助和奖励分类id\'',
            'subsidy_rules_unit_price' => Schema::TYPE_DECIMAL . '(10,2) NOT NULL COMMENT \'单价，例如：10元/单，5元/小时，50元/月\'',
            'isdel' => Schema::TYPE_SMALLINT. '(1) DEFAULT 0 COMMENT \'是否被删除，0为启用，1为删除\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11)  COMMENT \'更新时间\'',
            'created_at' => Schema::TYPE_INTEGER. '(11) COMMENT \'创建时间\'',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%subsidy_rules}}');
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
