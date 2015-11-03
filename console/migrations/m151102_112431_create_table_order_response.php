<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151102_112431_create_table_order_response extends Migration
{
    const TB_NAME = '{{%order_response}}';

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'订单响应\'';
        }

        $this->createTable(self::TB_NAME, [
            'id' => Schema::TYPE_PK . '(11) NOT NULL AUTO_INCREMENT COMMENT \'ID\'',
            'order_id' => Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT \'订单ID\'',
            'order_operation_user' => Schema::TYPE_STRING . '(100) DEFAULT NULL COMMENT \'操作者名称\'' ,
            'order_response_times' => Schema::TYPE_SMALLINT . '(1) NOT NULL COMMENT \'订单第几次的响应\'',
            'order_reply_result' => Schema::TYPE_SMALLINT . '(2) NOT NULL COMMENT \'接听结果,代码对应的信息见dbbass里的model\'',
            'order_response_or_not' => Schema::TYPE_SMALLINT . '(1) NOT NULL COMMENT \'是否还需要响应\'',
            'order_response_result' => Schema::TYPE_SMALLINT . '(2) NOT NULL COMMENT \'响应结果,代码对应的信息见dbbass里的model\'',
            'is_softdel' => Schema::TYPE_SMALLINT . '(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT \'状态\'',
            'created_at' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'编辑时间\'',
            'remark' => Schema::TYPE_STRING . '(512) NOT NULL DEFAULT "" COMMENT \'备注\'',
        ], $tableOptions);

    }

    public function safeDown()
    {
        $this->dropTable(self::TB_NAME);
    }
}
