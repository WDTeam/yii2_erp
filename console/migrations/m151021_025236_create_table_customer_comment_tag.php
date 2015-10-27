<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151021_025236_create_table_customer_comment_tag extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'评价标签表\'';
        }
        $this->createTable('{{%customer_comment_tag}}', [
            'id' => Schema::TYPE_PK .'  AUTO_INCREMENT ',
	    'customer_tag_name' => Schema::TYPE_STRING .'(255) NOT NULL COMMENT \'评价标签名称\'',
        'customer_comment_level' => Schema::TYPE_SMALLINT .'(4)  DEFAULT 0 COMMENT \'评价等级\'',
		'customer_tag_type' => Schema::TYPE_SMALLINT .'(2)  DEFAULT 0 COMMENT \'1 评价 2 退款 3 其他\'',
		'is_online'  => Schema::TYPE_BOOLEAN . '(1) DEFAULT 0 COMMENT \'是否上线\'',
            'created_at'  => Schema::TYPE_INTEGER . '(10) DEFAULT 0 COMMENT \'创建时间\'',
            'updated_at'  => Schema::TYPE_INTEGER . '(10) DEFAULT 0 COMMENT \'更新时间\'',
            'is_del'  => Schema::TYPE_BOOLEAN . '(1) DEFAULT 0 COMMENT \'删除\'',

        ], $tableOptions);
		$this->createIndex('customer_comment_level','{{%customer_comment_tag}}','customer_comment_level');
        $this->execute(
			"INSERT INTO `ejj_customer_comment_tag` VALUES ('1', '1', '阿姨态度很好', '1', '1', '1445860384', '1445860384', '0');
			INSERT INTO {{%customer_comment_tag}} VALUES ('2', '1', '阿姨比较热情', '1', '1', '1445860384', '1445860384', '0');
			INSERT INTO {{%customer_comment_tag}} VALUES ('3', '1', '态度很差', '3', '1', '1445860384', '1445860384', '0');
			INSERT INTO {{%customer_comment_tag}} VALUES ('4', '1', '蛮不讲理', '3', '1', '1445860384', '1445860384', '0');
			INSERT INTO {{%customer_comment_tag}} VALUES ('5', '1', '态度积极', '1', '1', '1445860384', '1445860384', '0');
			INSERT INTO {{%customer_comment_tag}} VALUES ('6', '1', '通情达理', '1', '1', '1445860384', '1445860384', '0');
			INSERT INTO {{%customer_comment_tag}} VALUES ('7', '1', '积极主动', '1', '1', '1445860384', '1445860384', '0');
			INSERT INTO {{%customer_comment_tag}} VALUES ('8', '2', '积极主动', '1', '1', '1445860384', '1445860384', '0');"
  );
    }





    public function down()
    {
        $this->dropTable("{{%customer_comment_tag}}");
        return true;
    }

}
