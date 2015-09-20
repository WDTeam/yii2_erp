<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_131959_ejj_loop_advertisement extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'手机端广告轮播图\'';
        }
        $this->createTable('{{%loop_advertisement}}', [
            'id'=>Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'编号\'' ,
            'loop_advertisement_pic'=>Schema::TYPE_STRING . '(32) DEFAULT NULL COMMENT \'广告图片\'',
            'loop_advertisement_url'=>Schema::TYPE_STRING . '(32) DEFAULT NULL COMMENT \'连接地址\'',
            'loop_advertisement_sort'=>Schema::TYPE_SMALLINT . '(4) DEFAULT 0 COMMENT \'排序\'',
            'created_at'=>Schema::TYPE_INTEGER . '(11) DEFAULT 0 COMMENT \'创建时间\'',
            'updated_at'=>Schema::TYPE_INTEGER . '(11) DEFAULT 0 COMMENT \'修改时间\'',
            'is_del'=>Schema::TYPE_SMALLINT . '(4) DEFAULT 0 COMMENT \'是否已经逻辑删除,1为已删除\'',
            ]);
    }

    public function down()
    {
        $this->dropTable('{{%loop_advertisement}}');
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
