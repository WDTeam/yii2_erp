<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151021_035240_create_table_customer_comment_level extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'评价等级表\'';
        }
        $this->createTable('{{%customer_comment_level}}', [
            'id' => Schema::TYPE_PK .'  AUTO_INCREMENT ',
	    'customer_comment_level' => Schema::TYPE_SMALLINT .'(8) DEFAULT 0 COMMENT \'评价等级\'',
           'customer_comment_level_name' => Schema::TYPE_STRING  .'(255) DEFAULT NULL COMMENT \'评价等级\'',
            'created_at'  => Schema::TYPE_INTEGER . '(10) DEFAULT 0 COMMENT \'创建时间\'',
            'updated_at'  => Schema::TYPE_INTEGER . '(10) DEFAULT 0 COMMENT \'更新时间\'',
            'is_del'  => Schema::TYPE_BOOLEAN . '(1) DEFAULT 0 COMMENT \'删除\'',

        ], $tableOptions);
		$this->createIndex('customer_comment_level','{{%customer_comment_level}}','customer_comment_level');
		$this->execute(
			"INSERT INTO {{%customer_comment_level}} VALUES ('1', '0', '满意', '1446089631', '0', '0');
INSERT INTO {{%customer_comment_level}} VALUES ('2', '1', '一般', '1446089631', '0', '0');
INSERT INTO {{%customer_comment_level}} VALUES ('3', '2', '不满意', '1446089631', '0', '0');"
        );
    }

    public function down()
    {
        $this->dropTable("{{%customer_comment_level}}");
        return true;
    }
}
