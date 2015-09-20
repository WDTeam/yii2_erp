<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_074216_create_table_op_boot_page_city extends Migration
{
    public function up(){
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'启动页城市关联表\'';
        }
        $this->createTable('{{%op_boot_page_city}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'编号\'' ,
            'boot_page_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'启动页编号\'',
            'boot_page_city_list' => Schema::TYPE_TEXT . ' DEFAULT NULL COMMENT \'所适用城市的list （序列化存储）\'',
            'created_at' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'编辑时间\'',
        ], $tableOptions);
    } 

    public function down(){ 
        $this->dropTable('{{%op_boot_page_city}}');
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
