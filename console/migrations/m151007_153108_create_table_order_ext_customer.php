<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151007_153108_create_table_order_ext_customer extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions ='CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'订单客户信息表\'';
        }


        $this->createTable('{{%order_ext_customer}}', [
            'order_id'=> Schema::TYPE_BIGPK .' NOT NULL COMMENT \'订单id\'',

            //================================客户信息
            'customer_id' => Schema::TYPE_INTEGER.'(10) unsigned NOT NULL DEFAULT 0 COMMENT \'客户ID\'',
            'order_customer_phone' => Schema::TYPE_STRING .'(16) NOT NULL DEFAULT \'\' COMMENT \'客户手机号\'',

            'order_customer_need' => Schema::TYPE_STRING . '(255) DEFAULT \'\' COMMENT \'客户需求\'',
            'order_customer_memo' => Schema::TYPE_STRING . '(255) DEFAULT \'\' COMMENT \'客户备注\'',
            'comment_id' => Schema::TYPE_INTEGER.'(10) unsigned  DEFAULT 0 COMMENT \'评价id\'',
            'invoice_id' => Schema::TYPE_INTEGER.'(10) unsigned  DEFAULT 0 COMMENT \'发票id\'',
            'order_customer_hidden' => Schema::TYPE_BOOLEAN . '(1) unsigned  DEFAULT 0 COMMENT \'客户端是否已删除\'',

            'created_at' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'修改时间\'',

        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%order_ext_customer}}');

        return true;
    }
}
