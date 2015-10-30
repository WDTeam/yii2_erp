<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151026_084334_create_table_general_smslog extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'通用短信记录表\'';
        }
        $this->createTable('{{%general_smslog}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT COMMENT \'自增id\'' ,
            'general_smslog_mobiles' => Schema::TYPE_TEXT . ' DEFAULT NULL COMMENT \'手机号,最多100个\'',
            'general_smslog_msg' => Schema::TYPE_STRING . '(150) DEFAULT NULL COMMENT \'短信内容\'',
            'general_smslog_res' => Schema::TYPE_STRING . '(100) DEFAULT NULL COMMENT \'结果\'',
            'created_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'创建时间\'',
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%general_smslog}}');
    }
}
