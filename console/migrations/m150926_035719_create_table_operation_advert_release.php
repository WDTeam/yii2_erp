<?php

use yii\db\Schema;
use yii\db\Migration;

class m150926_035719_create_table_operation_advert_release extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'广告发布表\'';
        }
        $this->createTable('{{%operation_advert_release}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT COMMENT \'编号\'' ,
            'city_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'广告位置编号\'',
            'city_name' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'广告位置名称\'',
            'operation_release_contents' => Schema::TYPE_TEXT . ' DEFAULT NULL COMMENT \'发布详情\'',
            'created_at' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'编辑时间\'',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%operation_advert_release}}');

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
