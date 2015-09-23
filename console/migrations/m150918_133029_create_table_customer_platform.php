<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_133029_create_table_customer_platform extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'平台表\'';
        }
        $this->createTable('{{%customer_platform}}', [
            'id'=>  Schema::TYPE_PK.'(8) NOT NULL AUTO_INCREMENT COMMENT \'主键\'',
            'platform_name'=>  Schema::TYPE_STRING.'(16) NOT NULL COMMENT \'平台名称\'',
            'pid'=>  Schema::TYPE_INTEGER.'(8) DEFAULT 0 COMMENT \'父级id\'',
            'created_at'=>  Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'创建时间\'',
            'updated_at'=>  Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'更新时间\'',
            'is_del'=>  Schema::TYPE_SMALLINT.'(4) NOT NULL DEFAULT 0 COMMENT \'是否逻辑删除\'',
            ], $tableOptions);
        $this->batchInsert('{{%customer_platform}}',
            ['id','platform_name','pid','created_at','updated_at','is_del'],
            [
                [1,'手机APP',0, time(),time(), 0],
                [2,'安卓APP',1, time(),time(), 0],
                [3,'IOS APP',1, time(),time(), 0],
                [4,'微信',0, time(),time(), 0],
            ]);
    }

    public function down()
    {
        $this->dropTable('{{%customer_platform}}');
    }
}
