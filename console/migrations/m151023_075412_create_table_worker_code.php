<?php

use yii\db\Schema;
use yii\db\Migration;

class m151023_075412_create_table_worker_code extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'阿姨登陆验证码\'';
        }
        $this->createTable('{{%worker_code}}', [
            'id'=>  Schema::TYPE_PK.'(8) NOT NULL AUTO_INCREMENT COMMENT \'ID\'',
            'worker_code'=>  Schema::TYPE_STRING.'(8) NOT NULL COMMENT \'验证码\'',
            'worker_code_expiration' => Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'验证码过期时间\'',
            'worker_phone'=>  Schema::TYPE_STRING.'(11) DEFAULT NULL COMMENT \'阿姨电话\'',
            'created_at'=>  Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'创建时间\'',
            'updated_at'=>  Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'更新时间\'',
            'is_del'=>  Schema::TYPE_SMALLINT.'(4) DEFAULT 0 COMMENT \'逻辑删除\'',
            ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%worker_code}}');

        return true;
    }
}
