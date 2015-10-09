<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151009_113401_create_table_customer_ext_balance extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'客户余额表\'';
        }
        $this->createTable('{{%customer_ext_balance}}', [
            'id'=>  Schema::TYPE_PK.'(8) NOT NULL AUTO_INCREMENT COMMENT \'ID\'',
            'customer_id'=>  Schema::TYPE_INTEGER.'(8) COMMENT \'客户\'',
            'customer_balance' => Schema::TYPE_DECIMAL.'(8,2) COMMENT \'客户余额\'',
            'created_at'=>  Schema::TYPE_INTEGER.'(11) COMMENT \'创建时间\'',
            'updated_at'=>  Schema::TYPE_INTEGER.'(11) COMMENT \'更新时间\'',
            'is_del'=>  Schema::TYPE_SMALLINT.'(4) DEFAULT NULL COMMENT \'是否删除\'',
            ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%customer_ext_balance}}');

        return true;
    }
}
