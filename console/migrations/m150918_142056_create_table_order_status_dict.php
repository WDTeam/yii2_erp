<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_142056_create_table_order_status_dict extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'订单状态表\'';
        }


        $this->createTable('{{%order_status_dict}}', [
            'id' => Schema::TYPE_PK . ' NOT NULL AUTO_INCREMENT COMMENT \'ID\'',
            'order_status_name' => Schema::TYPE_STRING . '(255) NOT NULL COMMENT \'状态名称服务端\'',
            'order_status_alias' => Schema::TYPE_STRING . '(255) NOT NULL DEFAULT 0 COMMENT \'状态名称客户端\'',
            'created_at' => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL DEFAULT 0',
            'updated_at' => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL DEFAULT 0',
            'isdel' => Schema::TYPE_SMALLINT . '(1) unsigned NOT NULL DEFAULT 0',
        ], $tableOptions);

        $this->batchInsert('{{%order_status_dict}}',
            ['id', 'order_status_name', 'order_status_alias',  'created_at', 'updated_at', 'isdel'],
            [
                [0, '创建订单','订单创建',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
                [1, '初始化','初始化',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
                [2, '付款中','付款中',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
                [3, '已付款','已付款',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
                [4, '待指派','待指派',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
                [5, '系统指派中','系统指派中',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
                [6, '待系统分单','待系统分单',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
                [7, '人工派单中','人工派单中',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
                [8, '人工派单失败','人工派单失败',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
                [9, '待服务','待服务',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
                [10, '服务中','服务中',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
                [11, '服务完成带评价','服务完成带评价',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
                [12, '订单完成','订单完成',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
                [13, '工人已结算','工人已结算',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
                [14, '第三方已对账','第三方已对账',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
                [15, '门店已结算','门店已结算',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
                [16, '取消订单','取消订单',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
                [17, '已退款','已退款',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
            ]
        );
    }

    public function down()
    {
        $this->dropTable('{{%order_status_dict}}');
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
