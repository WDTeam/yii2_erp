<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m150928_065602_create_table_customer_block_reason extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'客户加入黑名单原因选项表\'';
        }
        $this->createTable('{{%customer_block_reason}}', [
            'id'=>  Schema::TYPE_PK.'(8) NOT NULL AUTO_INCREMENT COMMENT \'主键\'',
            'customer_block_reason_option'=> Schema::TYPE_STRING.'(64) NOT NULL COMMENT \'客户加入黑名单选项\'',
            'created_at'=>  Schema::TYPE_INTEGER.'(11) COMMENT \'创建时间\'',
            'update_at'=>  Schema::TYPE_INTEGER.'(11) COMMENT \'更新时间\'',
            'is_del'=> Schema::TYPE_INTEGER.'(11) COMMENT \'是否逻辑删除\'',
            ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%customer_block_reason}}');
    }
}
