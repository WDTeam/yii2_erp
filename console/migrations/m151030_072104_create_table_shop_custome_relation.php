<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151030_072104_create_table_shop_custome_relation extends Migration
{
    
	
	
	 public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'用户和家政公司及门店关系表\'';
        }

        $this->createTable('{{%shop_custome_relation}}', [
            'id' => Schema::TYPE_PK . '(5) AUTO_INCREMENT  COMMENT \'主键id\'',
            'system_user_id' => Schema::TYPE_SMALLINT . '(10) DEFAULT 0 COMMENT \'用户id\'',
			'baseid' => Schema::TYPE_SMALLINT . '(6) DEFAULT 0 COMMENT \'父级id\'',
			'shopid' => Schema::TYPE_SMALLINT . '(10) DEFAULT 0 COMMENT \'门店id\'',
			'shop_manager_id' => Schema::TYPE_SMALLINT . '(5) DEFAULT 0 COMMENT \'管理公司id\'',
			'stype' => Schema::TYPE_SMALLINT . '(2) DEFAULT \'0\' COMMENT \'1 家政公司 2 门店\'',
            'is_del' => Schema::TYPE_SMALLINT . '(1) DEFAULT \'0\' COMMENT \'0 正常 1 删除\'',
        ], $tableOptions);
    }

    public function safeDown()
    {
		$this->dropTable('{{%shop_custome_relation}}');
		return true;
    }
}
