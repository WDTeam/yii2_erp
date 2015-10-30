<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151026_060203_create_table_worker_skill extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'阿姨技能关联表\'';
        }
        $this->createTable('{{%worker_skill}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT COMMENT \'阿姨技能关联表自增id\'' ,
            'worker_id' => Schema::TYPE_INTEGER . '(10) DEFAULT NULL COMMENT \'阿姨id\'',
            'worker_skill_id' => Schema::TYPE_INTEGER . '(10) DEFAULT NULL COMMENT \'阿姨技能配置表id\'',
            'created_ad' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'创建时间\'',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%worker_skill}}');
    }
}
