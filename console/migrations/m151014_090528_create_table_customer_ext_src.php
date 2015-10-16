<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151014_090528_create_table_customer_ext_src extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'客户来源记录表\'';
        }
        $this->createTable('{{%customer_ext_src}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'编号\'' ,
            'customer_id' => Schema::TYPE_INTEGER . '(11) DEFAULT 0 COMMENT \'客户\'',
            'platform_id' => Schema::TYPE_INTEGER . '(8) DEFAULT 0 COMMENT \'平台\'',
            'channal_id' => Schema::TYPE_INTEGER . '(8) DEFAULT 0 COMMENT \'渠道\'',
            'platform_name' => Schema::TYPE_STRING . '(255) DEFAULT NULL COMMENT \'平台名称\'',
            'channal_name' => Schema::TYPE_STRING . '(255) DEFAULT NULL COMMENT \'聚道名称\'',
            'platform_ename' => Schema::TYPE_STRING . '(255) DEFAULT NULL COMMENT \'平台拼音\'',
            'channal_ename' => Schema::TYPE_STRING . '(255) DEFAULT NULL COMMENT \'聚道拼音\'',
            'device_name'=>Schema::TYPE_STRING . '(255) DEFAULT NULL COMMENT \'设备名称\'',
            'device_no'=>Schema::TYPE_STRING . '(255) DEFAULT NULL COMMENT \'设备号码\'',
            'created_at'=>  Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'创建时间\'' ,
            'updated_at'=>  Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'更新时间\'' ,
            'is_del'=>  Schema::TYPE_SMALLINT.'(4) NOT NULL DEFAULT 0 COMMENT \'是否逻辑删除\'' ,
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%customer_ext_src}}');
    }
}
