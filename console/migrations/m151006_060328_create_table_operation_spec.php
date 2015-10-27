<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151006_060328_create_table_operation_spec extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'规格表\'';
        }
        $this->createTable('{{%operation_spec}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'编号\'' ,
            'operation_spec_name' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'规格名称\'',
            'operation_spec_description' => Schema::TYPE_TEXT . ' DEFAULT NULL COMMENT \'规格备注\'',
            'operation_spec_strategy_unit' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'计量单位\'',
            'operation_spec_values' => Schema::TYPE_TEXT . ' DEFAULT NULL COMMENT \'规格值(序列化属性)\'',
            'is_softdel' => Schema::TYPE_SMALLINT . '(1) unsigned NOT NULL DEFAULT 1 COMMENT \'状态\'',
            'created_at' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'编辑时间\'',
        ], $tableOptions);

        $this->execute(
            "INSERT INTO {{%operation_spec}} (`id`, `operation_spec_name`, `operation_spec_description`, `operation_spec_values`, `created_at`, `updated_at`, `operation_spec_strategy_unit`) VALUES
	        (2, '颜色', '颜色备注', 'a:3:{i:0;s:3:\"红\";i:1;s:3:\"蓝\";i:2;s:3:\"绿\";}', 1444136384, 1444225018, '件'),
	        (3, 'iphone', 'iphone备注', 'a:4:{i:0;s:12:\"16G合约机\";i:1;s:15:\"32G非合约机\";i:2;s:11:\"64合约机\";i:3;s:13:\"128G合约机\";}', 1444136584, 1444138455, '台');"
        );
    }

    public function down()
    {
        $this->dropTable('{{%operation_spec}}');
    }
}
