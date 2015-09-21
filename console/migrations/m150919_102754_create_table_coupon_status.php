<?php

use yii\db\Schema;
use yii\db\Migration;

class m150919_102754_create_table_coupon_status extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'优惠券\'';
        }
        $this->createTable('{{%coupon_status}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'主键\'',
            'coupon_id' => Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT \'关联优惠券表\'',
            'coupon_status_bundle_time'=>  Schema::TYPE_INTEGER. '(11) DEFAULT 0 COMMENT \'优惠券兑换成优惠券并绑定顾客时间\'',
            'coupon_status_use_time'=> Schema::TYPE_INTEGER . '(11) DEFAULT 0 COMMENT \'优惠券使用时间\'',
            'created_at'=>  Schema::TYPE_INTEGER . '(11) DEFAULT 0 COMMENT \'创建时间\'',
            'updated_at'=> Schema::TYPE_INTEGER . '(11) DEFAULT 0 COMMENT \'更新时间\'',
            'is_del'=> Schema::TYPE_SMALLINT . '(4) NOT NULL DEFAULT 0 COMMENT \'是否逻辑删除\'',
            ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%coupon_status}}');
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
