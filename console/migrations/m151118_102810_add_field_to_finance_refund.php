<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151118_102810_add_field_to_finance_refund extends Migration
{
    public function safeUp()
    {
    
		$this->addColumn('{{%finance_refund}}', 'order_money', 'decimal(8,2) DEFAULT NULL COMMENT \'order订单金额\' AFTER finance_refund_tel');
    

		 $this->addColumn('{{%finance_refund}}', 'order_use_acc_balance', 'decimal(8,2) DEFAULT NULL COMMENT \'使用余额\' AFTER finance_refund_money');
  
		
		$this->addColumn('{{%finance_refund}}', 'order_use_card_money', 'decimal(8,2) DEFAULT NULL COMMENT \'使用服务卡金额\' AFTER order_use_acc_balance');
    

		$this->addColumn('{{%finance_refund}}', 'order_use_promotion_money', 'decimal(8,2) DEFAULT NULL COMMENT \'使用促销金额\' AFTER order_use_card_money');
  
		
		$this->addColumn('{{%finance_refund}}', 'order_id', 'int(11) DEFAULT NULL COMMENT \'订单id\' AFTER finance_pay_channel_title'); 
    	
    }

    public function safeDown()
    {
    	$this->dropColumn('{{%finance_refund}}','order_money');
    	$this->dropColumn('{{%finance_refund}}','order_use_acc_balance');
    	$this->dropColumn('{{%finance_refund}}','order_use_card_money');
    	$this->dropColumn('{{%finance_refund}}','order_use_promotion_money');
    	$this->dropColumn('{{%finance_refund}}','order_id');	
    }
}
