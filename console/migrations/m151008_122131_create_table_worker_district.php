<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151008_122131_create_table_worker_district extends Migration
{
    public function Up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'阿姨绑定商圈表\'';
        }
        $this->createTable('{{%worker_district}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT COMMENT \'自增id\'' ,
            'worker_id'  => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'阿姨id\'',
            'operation_shop_district_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'商圈id\'',
            'created_ad'  => Schema::TYPE_INTEGER . '(10)  DEFAULT NULL COMMENT \'绑定时间\'',
        ], $tableOptions);

    }

    public function Down()
    {
        $this->dropTable('{{%worker_district}}');
    }
}
