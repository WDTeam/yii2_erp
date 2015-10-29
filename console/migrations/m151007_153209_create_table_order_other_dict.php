<?php

use yii\db\Schema;
use yii\db\Migration;

class m151007_153209_create_table_order_other_dict extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'订单状态表\'';
        }


        $this->createTable('{{%order_other_dict}}', [
            'id' => Schema::TYPE_PK . ' NOT NULL AUTO_INCREMENT COMMENT \'ID\'',
            'order_other_dict_type' => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL DEFAULT 0 COMMENT \'字典类型 1取消订单公司原因 2取消订单个人原因 3订单阿姨关系状态\'',
            'order_other_dict_name' => Schema::TYPE_STRING . '(255) NOT NULL COMMENT \'字典名称\'',
            'created_at' => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL DEFAULT 0',
            'updated_at' => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL DEFAULT 0',
            'isdel' => Schema::TYPE_SMALLINT . '(1) unsigned NOT NULL DEFAULT 0',
        ], $tableOptions);

        $this->batchInsert('{{%order_other_dict}}',
            ['id', 'order_other_dict_type', 'order_other_dict_name', 'created_at', 'updated_at', 'isdel'],
            [
                [1, 3,'人工指派未响应',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
                [2, 3,'人工指派拒单',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
                [3, 3,'已取消指派',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
                [4, 3,'IVR已推送',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
                [5, 3,'IVR推送失败',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
                [6, 3,'极光已推送',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
                [7, 3,'极光推送失败',YII_BEGIN_TIME,YII_BEGIN_TIME,0],

            ]
        );
    }

    public function down()
    {
        $this->dropTable('{{%order_other_dict}}');

        return true;
    }


}
