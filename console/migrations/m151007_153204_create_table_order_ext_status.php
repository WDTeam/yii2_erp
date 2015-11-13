<?php

use yii\db\Schema;
use yii\db\Migration;

class m151007_153204_create_table_order_ext_status extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions ='CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'订单状态表\'';
        }


        $this->createTable('{{%order_ext_status}}', [
            'order_id'=> Schema::TYPE_BIGPK .' NOT NULL COMMENT \'订单id\'',

            'order_before_status_dict_id' => Schema::TYPE_SMALLINT . '(4) unsigned NOT NULL DEFAULT 0 COMMENT \'状态变更前订单状态字典ID\'',
            'order_before_status_name' => Schema::TYPE_STRING . '(128) NOT NULL  DEFAULT \'\' COMMENT \'状态变更前订单状态\'',

            'order_status_dict_id' => Schema::TYPE_SMALLINT . '(4) unsigned NOT NULL DEFAULT 0 COMMENT \'订单状态字典ID\'',
            'order_status_name' => Schema::TYPE_STRING . '(128) NOT NULL  DEFAULT \'\' COMMENT \'订单状态\'',
            'order_status_boss' => Schema::TYPE_STRING . '(255) NOT NULL DEFAULT 0 COMMENT \'BOOS状态名称\'',
            'order_status_customer' => Schema::TYPE_STRING . '(255) NOT NULL DEFAULT 0 COMMENT \'客户端状态名称\'',
            'order_status_worker' => Schema::TYPE_STRING . '(255) NOT NULL DEFAULT 0 COMMENT \'阿姨端状态名称\'',

            'created_at' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'修改时间\'',

        ], $tableOptions);

        $this->createIndex('idx-order_ext_status-order_status_dict_id', '{{%order_ext_status}}', 'order_status_dict_id');
    }

    public function down()
    {
        $this->dropTable('{{%order_ext_status}}');

        return true;
    }


}
