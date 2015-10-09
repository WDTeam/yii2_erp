<?php

use yii\db\Schema;
use yii\db\Migration;

class m150921_054912_create_table_coupon_status_info extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'优惠券状态快照表\'';
        }
        $this->createTable('{{%coupon_status_info}}', [
            'id'=>  Schema::TYPE_PK.'(8) NOT NULL AUTO_INCREMENT COMMENT \'主键\'',
            'created_at'=> Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'创建时间\'',
            'updated_at'=> Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'更新时间\'',
            'is_del'=> Schema::TYPE_SMALLINT . '(4) NOT NULL DEFAULT 0 COMMENT \'是否逻辑删除\'',
            'coupon_status_info_name'=> Schema::TYPE_TEXT.'(255) NOT NULL COMMENT \'状态名称\'',
            'coupon_status_flow'=> Schema::TYPE_TEXT.'(255) NOT NULL COMMENT \'大体流程\'',
            'coupon_status_oper'=> Schema::TYPE_TEXT.'(255) NOT NULL COMMENT \'对应操作\'',
            'coupon_status_oper_name'=> Schema::TYPE_TEXT.'(255) NOT NULL COMMENT \'对应操作名称\'',
            ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%coupon_status_info}}');

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
