<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_154702_create_table_customer_comment extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'评价表\'';
        }
        $this->createTable('{{%customer_comment}}', [
            'id' => Schema::TYPE_PK .'  AUTO_INCREMENT ',
            'order_id' => Schema::TYPE_INTEGER .'(10) unsigned NOT NULL COMMENT \'订单ID\'',
            'customer_id' => Schema::TYPE_INTEGER .'(10) unsigned NOT NULL COMMENT \'用户ID\'',

			'worker_id' => Schema::TYPE_INTEGER .'(10) DEFAULT NULL COMMENT \'阿姨id\'',
			'worker_tel' => Schema::TYPE_STRING .'(15) DEFAULT NULL COMMENT \'阿姨电话\'',
			'operation_shop_district_id' => Schema::TYPE_INTEGER .'(5) DEFAULT NULL COMMENT \'商圈id\'',
			'province_id' => Schema::TYPE_INTEGER .'(6) DEFAULT NULL COMMENT \'省id\'',
			'city_id' => Schema::TYPE_INTEGER .'(6) DEFAULT NULL COMMENT \'市id\'',
			'county_id' => Schema::TYPE_INTEGER .'(6) DEFAULT NULL COMMENT \'区id\'',
            'customer_comment_phone' => Schema::TYPE_STRING .'(11) NOT NULL COMMENT \'用户电话\'',
            'customer_comment_content' => Schema::TYPE_STRING .'(255) DEFAULT \'\' COMMENT \'评论内容\'',
            'customer_comment_level' => Schema::TYPE_INTEGER .'(4) DEFAULT 0 COMMENT \'评论等级\'',
		'customer_comment_level_name' => Schema::TYPE_STRING .'(255) DEFAULT NULL COMMENT \'评价等级名称\'',
		'customer_comment_tag_ids' => Schema::TYPE_STRING .'(255) DEFAULT NULL COMMENT \'评价标签\'',
		'customer_comment_tag_names' => Schema::TYPE_STRING .'(255) DEFAULT NULL COMMENT \'评价标签名称\'',
            'customer_comment_anonymous' => Schema::TYPE_BOOLEAN .'(1) unsigned NOT NULL DEFAULT 0 COMMENT \'是否匿名评价,0匿名,1非匿名\'',
            'created_at'  => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL DEFAULT 0 COMMENT \'创建时间\'',
            'updated_at'  => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL DEFAULT 0 COMMENT \'更新时间\'',
            'is_del'  => Schema::TYPE_BOOLEAN . '(1) unsigned NOT NULL DEFAULT 1 COMMENT \'删除\'',
        ], $tableOptions);

        $this->createIndex('customer_id','{{%customer_comment}}','customer_id');
        $this->createIndex('order_id','{{%customer_comment}}','order_id');
    }


    public function down()
    {
        $this->dropTable("{{%customer_comment}}");
        return true;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
