<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_154702_create_table_user_comment extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'交易记录表\'';
        }
        $this->createTable('{{%user_comment}}', [
            'id' => Schema::TYPE_PK .'  AUTO_INCREMENT ',
            'order_id' => Schema::TYPE_INTEGER .'(10) unsigned NOT NULL COMMENT \'订单ID\'',
            'customer_id' => Schema::TYPE_INTEGER .'(10) unsigned NOT NULL COMMENT \'用户ID\'',
            'user_comment_phone' => Schema::TYPE_STRING .'(11) NOT NULL COMMENT \'用户电话\'',
            'user_comment_content' => Schema::TYPE_STRING .'(255) DEFAULT \'\' COMMENT \'评论内容\'',
            'user_comment_star_rate' => Schema::TYPE_BOOLEAN .'(1) unsigned NOT NULL DEFAULT 0 COMMENT \'评论星级,0为评价,1-5星\'',
            'user_comment_anonymous' => Schema::TYPE_BOOLEAN .'(1) unsigned NOT NULL DEFAULT 0 COMMENT \'是否匿名评价,0匿名,1非匿名\'',
            'created_at'  => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL DEFAULT 0 COMMENT \'创建时间\'',
            'updated_at'  => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL DEFAULT 0 COMMENT \'更新时间\'',
            'is_del'  => Schema::TYPE_BOOLEAN . '(1) unsigned NOT NULL DEFAULT 1 COMMENT \'删除\'',

        ], $tableOptions);

        $this->createIndex('customer_id','{{%user_comment}}','customer_id');
        $this->createIndex('order_id','{{%user_comment}}','order_id');

    }
    

    public function down()
    {
        $this->dropTable("{{%user_comment}}");
        return false;
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
