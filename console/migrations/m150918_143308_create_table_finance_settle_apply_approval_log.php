<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_143308_create_table_finance_settle_apply_approval_log extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'结算申请审批日志表\'';
        }
        $this->createTable('{{%finance_settle_apply_approval_log}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'主键\'' ,
            'finance_settle_apply_id' => Schema::TYPE_INTEGER . '(10)  COMMENT \'结算申请id\'',
            'finance_settle_apply_code' => Schema::TYPE_STRING . '(32)  COMMENT \'结算编号\'',
            'finance_settle_apply_reviewer_id' => Schema::TYPE_INTEGER . '(10)  COMMENT \'审核人员Id\'',
            'finance_settle_apply_reviewer' => Schema::TYPE_STRING . '(20)  COMMENT \'审核人员姓名\'',
            'finance_settle_apply_node_id' => Schema::TYPE_INTEGER . '(10)  COMMENT \'审核节点id\'',
            'finance_settle_apply_node_des' => Schema::TYPE_TEXT . ' COMMENT \'审核描述\'',
            'finance_settle_apply_is_passed' => Schema::TYPE_INTEGER . '(1)  COMMENT \'审核是否通过，0审核未通过，1审核通过\'',
            'finance_settle_apply_reviewer_comment' => Schema::TYPE_TEXT . ' COMMENT \'审核人员意见\'',
            'is_softdel' => Schema::TYPE_SMALLINT. '(1) DEFAULT 0 COMMENT \'是否被删除，0为启用，1为删除\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11)  COMMENT \'更新时间\'',
            'created_at' => Schema::TYPE_INTEGER. '(11) COMMENT \'创建时间\'',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%finance_settle_apply_approval_log}}');
        return true;
    }
}
