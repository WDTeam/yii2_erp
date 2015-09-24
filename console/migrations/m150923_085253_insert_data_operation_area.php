<?php

use yii\db\Schema;
use yii\db\Migration;

class m150923_085253_insert_data_operation_area extends Migration
{
    public function up()
    {
        $sql = str_replace('`areas`','{{%operation_area}}', file_get_contents('./datasql/areas.sql'));
        $this->execute($sql);
    }

    public function down()
    {
        $sql = 'transcate {{%operation_area}}';
        $this->execute($sql);
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
