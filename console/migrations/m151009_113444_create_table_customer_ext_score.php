<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151009_113444_create_table_customer_ext_score extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'客户积分表\'';
        }
        $this->createTable('{{%customer_ext_score}}', [
            'id'=>  Schema::TYPE_PK.'(8) NOT NULL AUTO_INCREMENT COMMENT \'ID\'',
            'customer_id'=>  Schema::TYPE_INTEGER.'(8) COMMENT \'客户\'',
			'customer_phone' => Schema::TYPE_STRING . '(11) DEFAULT NULL COMMENT \'手机号\'',
            'customer_score' => Schema::TYPE_INTEGER.'(8) COMMENT \'客户积分\'',
            'created_at'=>  Schema::TYPE_INTEGER.'(11) COMMENT \'创建时间\'',
            'updated_at'=>  Schema::TYPE_INTEGER.'(11) COMMENT \'更新时间\'',
            'is_del'=>  Schema::TYPE_SMALLINT.'(4) DEFAULT NULL COMMENT \'是否删除\'',
            ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%customer_ext_score}}');

        return true;
    }
}
