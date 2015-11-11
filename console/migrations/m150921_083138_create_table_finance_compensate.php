<?php

use yii\db\Schema;
use yii\db\Migration;

class m150921_083138_create_table_finance_compensate extends Migration
{
     public function up()
    {

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'赔偿管理\'';
        }
        $this->createTable('{{%finance_compensate}}', [
            'id' => Schema::TYPE_PK .' AUTO_INCREMENT COMMENT \'主键id\'' ,
            'finance_compensate_code' => Schema::TYPE_STRING .'(32) COMMENT \'赔偿编号\'' ,
            'finance_compensate_oa_code' => Schema::TYPE_STRING . '(40)  COMMENT \'OA批号\'' ,
            'finance_complaint_id' => Schema::TYPE_INTEGER.'(10) DEFAULT NULL COMMENT \'投诉Id\'' , 
            'finance_complaint_code' => Schema::TYPE_STRING.'(32) DEFAULT NULL COMMENT \'投诉编号\'' , 
            'order_id' => Schema::TYPE_INTEGER.'(10)  DEFAULT NULL COMMENT \'订单Id\'' , 
            'order_code' => Schema::TYPE_STRING.'(32)  DEFAULT NULL COMMENT \'订单编号\'' ,
            'worker_id' => Schema::TYPE_INTEGER.'(10)  DEFAULT NULL COMMENT \'阿姨Id\'' ,  
            'worker_tel' => Schema::TYPE_STRING.'(11)  DEFAULT NULL COMMENT \'阿姨电话\'' ,  
            'worker_name' => Schema::TYPE_STRING.'(20)  DEFAULT NULL COMMENT \'阿姨姓名\'' , 
            'customer_id' => Schema::TYPE_INTEGER.'(10)  DEFAULT NULL COMMENT \'客户Id\'' ,    
            'customer_name' => Schema::TYPE_STRING.'(20)  DEFAULT NULL COMMENT \'客户名称\'' ,    
            'finance_compensate_coupon' => Schema::TYPE_STRING . '(150) DEFAULT NULL COMMENT \' 优惠券,可能是多个优惠券，用分号分隔\'' ,
            'finance_compensate_coupon_money' => Schema::TYPE_STRING . '(150) DEFAULT NULL COMMENT \' 优惠券金额,可能是多个优惠券金额，用分号分隔\'' ,
            'finance_compensate_money' => Schema::TYPE_DECIMAL . '(10,2) DEFAULT NULL COMMENT \' 赔偿金额\'' ,
            'finance_compensate_total_money' => Schema::TYPE_DECIMAL . '(10,2) DEFAULT NULL COMMENT \' 赔偿总金额\'' ,
            'finance_compensate_insurance_money' => Schema::TYPE_DECIMAL . '(10,2) DEFAULT NULL COMMENT \' 保险理赔金额\'' ,
            'finance_compensate_company_money' => Schema::TYPE_DECIMAL . '(10,2) DEFAULT NULL COMMENT \' 公司理赔金额\'' ,
            'finance_compensate_worker_money' => Schema::TYPE_DECIMAL . '(10,2) DEFAULT NULL COMMENT \' 阿姨赔付金额\'' ,
            'finance_compensate_reason' => Schema::TYPE_TEXT . ' DEFAULT NULL COMMENT \'赔偿原因\'' ,
            'finance_compensate_proposer' => Schema::TYPE_STRING . '(20) DEFAULT NULL COMMENT \'申请人\'' ,
            'finance_compensate_auditor' => Schema::TYPE_STRING . '(20) DEFAULT NULL COMMENT \'审核人\'' ,
            'finance_compensate_status' => Schema::TYPE_INTEGER . '(1)  DEFAULT 0 COMMENT \'赔偿状态，0 申请赔偿，待审核;1已赔偿;-1 审核未通过\'' ,
            'comment' => Schema::TYPE_TEXT . ' DEFAULT NULL COMMENT \'备注，可能是未通过原因\'' ,
            'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL  COMMENT \'审核时间\'',
            'created_at' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'申请时间\'',
            'is_softdel' => Schema::TYPE_SMALLINT. '(1) DEFAULT \'0\' COMMENT \'0 正常 1删除\'' ,
        ], $tableOptions);
    }


    public function down()
    {
        $this->dropTable('{{%finance_compensate}}');

        return true;
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
