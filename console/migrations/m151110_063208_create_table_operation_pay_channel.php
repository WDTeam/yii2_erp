<?php

use yii\db\Schema;
use yii\db\Migration;

class m151110_063208_create_table_operation_pay_channel extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'支付渠道表\'';
        }
        $this->createTable('{{%operation_pay_channel}}', [
            'id'=>  Schema::TYPE_PK.'(5) NOT NULL AUTO_INCREMENT COMMENT \'主键id\'',
            'operation_pay_channel_name'=>  Schema::TYPE_STRING.'(50) DEFAULT NULL COMMENT \'支付渠道名称\'',
            'operation_pay_channel_type' => Schema::TYPE_SMALLINT.'(1) DEFAULT 1 COMMENT \'支付渠道类别\'',
            'operation_pay_channel_rate'=>  Schema::TYPE_STRING.'(6) DEFAULT 1 COMMENT \'比率\'',
            'system_user_id'=>  Schema::TYPE_SMALLINT.'(2) DEFAULT 0 COMMENT \'添加人id\'',
            'system_user_name'=>  Schema::TYPE_STRING.'(40) DEFAULT 0 COMMENT \'添加人名称\'',
            'create_time'=>  Schema::TYPE_INTEGER.'(10) DEFAULT NULL COMMENT \'增加时间\'',
            'is_del'=>  Schema::TYPE_SMALLINT.'(1) DEFAULT 0 COMMENT \'0 正常 1 删除\'',
            ], $tableOptions);
    }
    
    public function down()
    {
        $this->dropTable("{{%operation_pay_channel}}");
        return true;
    }
}
