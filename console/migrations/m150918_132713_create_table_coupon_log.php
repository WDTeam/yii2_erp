<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_132713_create_table_coupon_log extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'优惠券日志表\'';
        }
        $this->createTable('{{%coupon_log}}', [
            'id'=>  Schema::TYPE_PK.'(8) NOT NULL AUTO_INCREMENT COMMENT \'主键\'',
            'customer_id'=>  Schema::TYPE_INTEGER.'(8) NOT NULL COMMENT \'关联顾客\'',
            'coupon_id'=>  Schema::TYPE_INTEGER.'(8) NOT NULL COMMENT \'关联优惠券\'',
            'order_id'=>  Schema::TYPE_INTEGER.'(8) NOT NULL COMMENT \'关联订单\'',
            'coupon_log_type'=>  Schema::TYPE_SMALLINT.'(4) NOT NULL COMMENT \'1为绑定顾客，2为使用，3为退还\'',
            'coupon_log_price'=>  Schema::TYPE_DECIMAL.'(8,2) NOT NULL COMMENT \'实际优惠或者退还金额\'',
            'created_at'=> Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'创建时间\'',
            'updated_at'=> Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'更新时间\'',
            'is_del'=> Schema::TYPE_SMALLINT . '(4) NOT NULL DEFAULT 0 COMMENT \'是否逻辑删除\'',
            ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%coupon_log}}');

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
