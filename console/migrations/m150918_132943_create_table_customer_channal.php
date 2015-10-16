<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_132943_create_table_customer_channal extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'渠道表\'';
        }
        $this->createTable('{{%customer_channal}}', [
            'id'=>  Schema::TYPE_PK.'(8) NOT NULL AUTO_INCREMENT COMMENT \'主键\'' ,
            'channal_name'=>  Schema::TYPE_STRING.'(16) DEFAULT NULL COMMENT \'聚道名称\'' ,
            'channal_ename'=>  Schema::TYPE_STRING.'(16) DEFAULT NULL COMMENT \'聚道拼音\'' ,
            'pid'=>  Schema::TYPE_INTEGER.'(8) DEFAULT 0 COMMENT \'父级id\'' ,
            'created_at'=>  Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'创建时间\'' ,
            'updated_at'=>  Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'更新时间\'' ,
            'is_del'=>  Schema::TYPE_SMALLINT.'(4) NOT NULL DEFAULT 0 COMMENT \'是否逻辑删除\'' ,
            ], $tableOptions);
        // $this->batchInsert('{{%customer_channal}}',
        //     ['id','channal_name','pid','created_at','updated_at','is_del'],
        //     [
        //         [1,'美团',0, time(),time(), 0],
        //         [2,'支付宝服务窗',0, time(),time(), 0],
        //     ]);
    }

    public function down()
    {
        // 在删除表前，需要删除配置的数据
        $this->dropTable('{{%customer_channal}}');

        return true;
    }
}
