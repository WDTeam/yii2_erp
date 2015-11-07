<?php

use yii\db\Schema;
use yii\db\Migration;

class m151007_153207_create_table_order_src extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions ='CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'订单来源表\'';
        }


        $this->createTable('{{%order_src}}', [
            'id'=> Schema::TYPE_BIGPK .' NOT NULL AUTO_INCREMENT COMMENT \'编号\'',
            'created_at' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'修改时间\'',
            'order_src_name' => Schema::TYPE_STRING . '(128) NOT NULL DEFAULT \'\' COMMENT \'订单来源，订单入口名称\'',
            'isdel' => Schema::TYPE_BOOLEAN . '(1) unsigned NOT NULL DEFAULT 0 COMMENT \'是否已删除\'',
        ], $tableOptions);

        $this->batchInsert('{{%order_src}}',
            ['id','created_at','updated_at','order_src_name','isdel'],
            [
                [1,time(),time(),'BOSS',0],
                [2,time(),time(),'IOS',0],
                [3,time(),time(),'Android',0],
                [4,time(),time(),'POP',0],
                [5,time(),time(),'H5',0]
            ]
        );
    }

    public function down()
    {
        $this->dropTable('{{%order_src}}');

        return true;
    }


}
