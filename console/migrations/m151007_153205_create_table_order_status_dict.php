<?php

use yii\db\Schema;
use yii\db\Migration;

class m151007_153205_create_table_order_status_dict extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'订单状态表\'';
        }


        $this->createTable('{{%order_status_dict}}', [
            'id' => Schema::TYPE_PK . ' NOT NULL AUTO_INCREMENT COMMENT \'ID\'',
            'order_status_name' => Schema::TYPE_STRING . '(255) NOT NULL COMMENT \'状态名称\'',
            'order_status_boss' => Schema::TYPE_STRING . '(255) NOT NULL DEFAULT 0 COMMENT \'BOOS状态名称\'',
            'order_status_customer' => Schema::TYPE_STRING . '(255) NOT NULL DEFAULT 0 COMMENT \'客户端状态名称\'',
            'order_status_worker' => Schema::TYPE_STRING . '(255) NOT NULL DEFAULT 0 COMMENT \'阿姨端状态名称\'',
            'created_at' => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL DEFAULT 0',
            'updated_at' => Schema::TYPE_INTEGER . '(10) unsigned NOT NULL DEFAULT 0',
            'isdel' => Schema::TYPE_SMALLINT . '(1) unsigned NOT NULL DEFAULT 0',
        ], $tableOptions);

        $this->batchInsert('{{%order_status_dict}}',
            ['id', 'order_status_name', 'order_status_boss','order_status_customer', 'order_status_worker',  'created_at', 'updated_at', 'isdel'],
            [
                [1, '已创建','初始化','已下单','已下单',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
                [2, '待指派','待指派','待指派','待指派',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
                [3, '已开始智能指派','智能指派中','指派中','待指派',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
                [4, '已完成智能指派','待服务','待服务','待服务',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
                [5, '未完成智能指派','智能分单中','指派中','待指派',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
                [6, '已开始人工指派','人工派单中','指派中','待指派',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
                [7, '已完成人工指派','待服务','待服务','待服务',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
                [8, '未完成人工指派','人工派单失败','指派中','待指派',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
                [9, '阿姨已抢单','待服务','待服务','待服务',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
                [10, '已开始服务','服务中','服务中','服务中',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
                [11, '已完成服务','服务完成','服务完成请评价','待评价',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
                [12, '已完成评价','客户已确认','评价已完成','已完成',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
                [13, '已完成结算','工人已结算','评价已完成','已完成',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
                [14, '已取消','已取消','已关闭','已取消',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
                [15, '已归档','已归档','已删除','已删除',YII_BEGIN_TIME,YII_BEGIN_TIME,0],
            ]
        );
    }

    public function down()
    {
        $this->dropTable('{{%order_status_dict}}');

        return true;
    }


}
