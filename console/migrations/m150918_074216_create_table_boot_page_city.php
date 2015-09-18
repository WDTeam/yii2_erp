<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_074216_create_table_boot_page_city extends Migration
{
    public function up(){
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB COMMENT=\'启动页城市关联表\'';
        }
        $this->createTable('{{%boot_page_city}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'编号\'' ,
            'boot_page_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'启动页编号\'',
            'boot_page_city_list' => Schema::TYPE_TEXT . '(60) DEFAULT NULL COMMENT \'所适用城市的list （序列化存储）\'',
            'create_time' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'创建时间\'',
            'updatetime' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'编辑时间\'',
        ], $tableOptions);
    }

    public function down(){
        $this->dropTable('{{%boot_page_city}}');
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
