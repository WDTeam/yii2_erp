<?php

use yii\db\Schema;
use yii\db\Migration;

class m151021_092945_create_table_worker_access_token extends Migration
{
    public function safeUp()
    {
         $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'阿姨access_token\'';
        }
        $this->createTable('{{%worker_access_token}}', [
            'id'=>  Schema::TYPE_PK.'(8) NOT NULL AUTO_INCREMENT COMMENT \'ID\'',
            'worker_access_token'=>  Schema::TYPE_STRING.'(64) NOT NULL COMMENT \'access_token\'',
            'worker_access_token_expiration' => Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'access_token过期时间\'',
            'worker_code_id'=> Schema::TYPE_INTEGER.'(8) DEFAULT NULL COMMENT \'关联验证码\'',
            'worker_code'=> Schema::TYPE_STRING.'(8) DEFAULT NULL COMMENT \'验证码\'',
            'worker_phone'=>  Schema::TYPE_STRING.'(11) DEFAULT NULL COMMENT \'阿姨手机\'',
            'created_at'=>  Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'创建时间\'',
            'updated_at'=>  Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'更新时间\'',
            'is_del'=>  Schema::TYPE_SMALLINT.'(4) DEFAULT 0 COMMENT \'逻辑删除\'',
            ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%worker_access_token}}');

        return true;
    }
}
