<?php

use yii\db\Schema;
use yii\db\Migration;

class m150923_085253_insert_data_operation_area extends Migration
{
    public function up()
    {
        ini_set('memory_limit', '512M');
        $str = file_get_contents('./console/migrations/areas.sql');
        $str = str_replace('`', '', $str);
        $pat = '/\((.*?)\)/i';
        preg_match_all($pat, $str, $m);
        $rows = [];
        foreach ($m[1] as $value){
            $rows[] = explode(',', str_replace("'", '', $value));
        }
        unset($rows[0]);
//         var_dump($m[1][0], $rows[1]);exit;
        $this->batchInsert('{{%operation_area}}', explode(',', $m[1][0]), $rows);
    }

    public function down()
    {
        $sql = 'TRUNCATE {{%operation_area}}';
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