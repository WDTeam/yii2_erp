<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151115_110155_create_table_finance_worker_cannot_settle_order_reason extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'阿姨不能结算订单原因表，包括赔偿中、投诉中的订单\'';
        }
        $this->createTable('{{%finance_worker_cannot_settle_order_reason}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'主键\'' ,
            'exception_node_id' => Schema::TYPE_INTEGER . '(10) NOT NULL COMMENT \'异常节点id,例如赔偿id、投诉id\'',
            'exception_node_code' => Schema::TYPE_STRING . '(32) NOT NULL COMMENT \'异常节点编号\，例如赔偿编号、投诉编号\'',
            'exception_node_status' => Schema::TYPE_SMALLINT . '(1) NOT NULL COMMENT \'异常节点状态\'',
            'exception_node_comment' => Schema::TYPE_TEXT . '(1) NOT NULL COMMENT \'异常节点备注，包括赔偿原因、投诉原因等\'',
            'worker_id' => Schema::TYPE_INTEGER . '(10) NOT NULL COMMENT \'阿姨id\'',
            'order_id' => Schema::TYPE_INTEGER . '(10) NOT NULL COMMENT \'订单id\'',
            'order_code' => Schema::TYPE_STRING . '(32) NOT NULL COMMENT \'订单编号\'',
            'finance_worker_settle_apply_starttime' => Schema::TYPE_INTEGER . '(10)  COMMENT \'本次结算开始时间(统计)，例如：2015.9.1 00:00:00对应的int值\'',
            'finance_worker_settle_apply_endtime' => Schema::TYPE_INTEGER . '(10)  COMMENT \'本次结算结束时间(统计)，例如：2015.9.30 23:59:59对应的int值\'',
            'is_softdel' => Schema::TYPE_SMALLINT. '(1) DEFAULT 0 COMMENT \'是否被删除，0为启用，1为删除\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11)  COMMENT \'结算时间\'',
            'created_at' => Schema::TYPE_INTEGER. '(11) COMMENT \'创建时间\'',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%finance_worker_cannot_settle_order_reason}}');
        return true;
    }
}
