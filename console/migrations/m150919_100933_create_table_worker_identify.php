<?php

use yii\db\Schema;
use yii\db\Migration;

class m150919_100933_create_table_worker_identify extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'阿姨身份表\'';
        }
        $this->createTable('{{%worker_identify_config}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT COMMENT \'阿姨封号表自增id\'' ,
            'worker_identify_name' => Schema::TYPE_STRING . '(20) DEFAULT NULL COMMENT \'主表阿姨id\'',
            'created_ad' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'创建时间\'',
            'updated_ad' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'最后更新时间\'',
            'admin_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'操作管理员id\'',
            'isdel' => Schema::TYPE_DOUBLE . '(1) DEFAULT NULL COMMENT \'是否删除 0正常1删除\'',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('ejj_worker_identify_config');
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
