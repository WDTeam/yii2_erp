<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_075922_create_table_operation_category_type extends Migration
{
    public function up(){
        $sql = 'DROP TABLE IF EXISTS {{%operation_category_type}}';
        $this->execute($sql);
    }

    public function down(){
        //$this->dropTable('{{%operation_category_type}}');
        $sql = 'DROP TABLE IF EXISTS {{%operation_category_type}}';
        $this->execute($sql);

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
