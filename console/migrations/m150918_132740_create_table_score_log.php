<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_132740_ejj_score_log extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'积分日志表\'';
        }
        $this->createTable('{{%score_log}}', [
            'id'=>  Schema::TYPE_PK.'(8) NOT NULL AUTO_INCREMENT COMMENT \'主键\'',
            'customer_id'=>  Schema::TYPE_INTEGER.'(8) NOT NULL',
            'score_log_score'=> Schema::TYPE_INTEGER.'(8) NOT NULL COMMENT \'积分数量\'',
            'score_log_type'=>  Schema::TYPE_SMALLINT.'(4) NOT NULL COMMENT \'积分类型,1为支付完成获取，2为评论完成获取，3为分享朋友圈获取，-1为售后扣除，-2为兑换兑换pop商品或服务扣除，-3为兑换本平台优惠扣除，-4为兑换本平台免费服务扣除\'',
            'order_id'=>  Schema::TYPE_INTEGER.'(8) NOT NULL',
            'created_at'=>  Schema::TYPE_INTEGER.'(11) NOT NULL',
            'updated_at'=>  Schema::TYPE_INTEGER.'(11) NOT NULL',
            ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%score_log}}');
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
