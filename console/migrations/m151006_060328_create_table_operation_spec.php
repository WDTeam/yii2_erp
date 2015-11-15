<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151006_060328_create_table_operation_spec extends Migration
{
    const TB_NAME = '{{%operation_spec}}';

    public function up()
    {
        $sql = 'DROP TABLE IF EXISTS ' . self::TB_NAME;
        $this->execute($sql);

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'规格表\'';
        }
        $this->createTable(self::TB_NAME, [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'编号\'' ,
            'operation_spec_name' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'规格名称\'',
            'operation_spec_description' => Schema::TYPE_TEXT . ' DEFAULT NULL COMMENT \'规格备注\'',
            'operation_spec_strategy_unit' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'计量单位\'',
            'operation_spec_values' => Schema::TYPE_TEXT . ' DEFAULT NULL COMMENT \'规格值(序列化属性)\'',
            'is_softdel' => Schema::TYPE_SMALLINT . '(1) unsigned NOT NULL DEFAULT 0 COMMENT \'状态\'',
            'created_at' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'编辑时间\'',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable(self::TB_NAME);
    }
}
