<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151007_153047_create_table_order_ext_pop extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions ='CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'订单第三方信息表\'';
        }


        $this->createTable('{{%order_ext_pop}}', [
            'order_id'=> Schema::TYPE_BIGPK .' NOT NULL COMMENT \'订单id\'',

            //================================第三方信息
            'order_pop_order_code' => Schema::TYPE_STRING . '(255)  DEFAULT \'\' COMMENT \'第三方订单编号\'',
            'order_pop_group_buy_code' =>  Schema::TYPE_STRING . '(255)  DEFAULT \'\' COMMENT \'第三方团购码\'',
            'order_pop_operation_money' =>  Schema::TYPE_DECIMAL . '(8,2)  DEFAULT 0 COMMENT \'第三方运营费\'',
            'order_pop_order_money' =>  Schema::TYPE_DECIMAL . '(8,2) unsigned  DEFAULT 0 COMMENT \'第三方订单金额\'',
            'order_pop_pay_money' => Schema::TYPE_DECIMAL . '(8,2)  DEFAULT 0 COMMENT \'合作方结算金额 负数表示合作方结算规则不规律无法计算该值。\'',

            'created_at' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'修改时间\'',

        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%order_ext_pop}}');

        return true;
    }
}
