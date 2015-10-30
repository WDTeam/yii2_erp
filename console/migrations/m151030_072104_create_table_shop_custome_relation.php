<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151030_072104_create_table_shop_custome_relation extends Migration
{
    
	
	
	 public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'�û��ͼ�����˾���ŵ��ϵ��\'';
        }

        $this->createTable('{{%shop_custome_relation}}', [
            'id' => Schema::TYPE_PK . '(5) AUTO_INCREMENT  COMMENT \'����id\'',
            'system_user_id' => Schema::TYPE_SMALLINT . '(10) DEFAULT 0 COMMENT \'�û�id\'',
			'baseid' => Schema::TYPE_SMALLINT . '(6) DEFAULT 0 COMMENT \'����id\'',
			'shopid' => Schema::TYPE_SMALLINT . '(10) DEFAULT 0 COMMENT \'�ŵ�id\'',
			'shop_manager_id' => Schema::TYPE_SMALLINT . '(5) DEFAULT 0 COMMENT \'����˾id\'',
			'stype' => Schema::TYPE_SMALLINT . '(2) DEFAULT \'0\' COMMENT \'1 ������˾ 2 �ŵ�\'',
            'is_del' => Schema::TYPE_SMALLINT . '(1) DEFAULT \'0\' COMMENT \'0 ���� 1 ɾ��\'',
        ], $tableOptions);
    }

    public function safeDown()
    {
		$this->dropTable('{{%shop_custome_relation}}');
		return true;
    }
}
