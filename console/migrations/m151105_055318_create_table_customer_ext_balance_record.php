<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151105_055318_create_table_customer_ext_balance_record extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'客户余额记录表\'';
        }
        $this->createTable('{{%customer_ext_balance_record}}', [
            'id'=>  Schema::TYPE_PK.'(8) NOT NULL AUTO_INCREMENT COMMENT \'ID\'',
            'customer_id'=>  Schema::TYPE_INTEGER.'(8) COMMENT \'客户\'',
			'customer_phone' => Schema::TYPE_STRING . '(11) DEFAULT NULL COMMENT \'手机号\'',
            'customer_ext_balance_begin_balance' => Schema::TYPE_DECIMAL.'(8,2) COMMENT \'客户操作前余额\'',
            'customer_ext_balance_end_balance' => Schema::TYPE_DECIMAL.'(8,2) COMMENT \'客户操作后余额\'',
            'customer_ext_balance_operate_balance' => Schema::TYPE_DECIMAL.'(8,2) COMMENT \'客户操作余额量\'',
            'customer_ext_balance_operate_type' => Schema::TYPE_SMALLINT.'(4) COMMENT \'客户操作余额类型-1为减少1为增加0为不变\'',
            'customer_ext_balance_operate_type_name' => Schema::TYPE_STRING.'(255) COMMENT \'客户操作余额类型名称\'',
            'customer_ext_balance_trans_no' => Schema::TYPE_STRING.'(255) UNIQUE COMMENT \'交易记录号\'',
            'customer_ext_balance_trans_serial' => Schema::TYPE_STRING.'(255) UNIQUE COMMENT \'交易流水号\'',
            'created_at'=>  Schema::TYPE_INTEGER.'(11) COMMENT \'创建时间\'',
            'updated_at'=>  Schema::TYPE_INTEGER.'(11) COMMENT \'更新时间\'',
            'is_del'=>  Schema::TYPE_SMALLINT.'(4) DEFAULT NULL COMMENT \'是否删除\'',
            ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%customer_ext_balance_record}}');

        return true;
    }
}
