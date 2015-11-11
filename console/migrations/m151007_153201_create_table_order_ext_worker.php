<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151007_153201_create_table_order_ext_worker extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions ='CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'订单工人信息表\'';
        }


        $this->createTable('{{%order_ext_worker}}', [
            'order_id'=> Schema::TYPE_BIGPK .' NOT NULL COMMENT \'订单id\'',

            //===========================工人信息
            'worker_id' => Schema::TYPE_INTEGER.'(10) unsigned  DEFAULT 0 COMMENT \'工人id\'',
            'order_worker_phone' => Schema::TYPE_STRING.'(64) DEFAULT \'\' COMMENT \'工人手机号\'',
            'order_worker_name' => Schema::TYPE_STRING.'(64) DEFAULT \'\' COMMENT \'工人姓名\'',
            'worker_type_id' => Schema::TYPE_INTEGER.'(10) unsigned  DEFAULT 0 COMMENT \'工人职位类型ID\'',
            'order_worker_type_name' => Schema::TYPE_STRING.'(64)  DEFAULT \'\' COMMENT \'工人职位类型\'',
            'order_worker_assign_type' => Schema::TYPE_SMALLINT.'(4) unsigned  DEFAULT 0 COMMENT \'工人接单方式 0未接单 1客服指派 2门店指派 3阿姨端接单 4IVR接单\'',
            'shop_id' => Schema::TYPE_INTEGER.'(10) unsigned  DEFAULT 0 COMMENT \'工人所属门店id\'',
            'order_worker_shop_name' => Schema::TYPE_STRING . '(255) DEFAULT \'\' COMMENT \'工人所属门店名称\'',
            'order_worker_memo' => Schema::TYPE_STRING . '(255) DEFAULT \'\' COMMENT \'阿姨备注\'',
            'order_worker_assign_time' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'接单时间\'',

            'created_at' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'修改时间\'',

        ], $tableOptions);

        $this->createIndex('idx-order_ext_worker-worker_id', '{{%order_ext_worker}}', 'worker_id');
        $this->createIndex('idx-order_ext_worker-order_worker_phone', '{{%order_ext_worker}}', 'order_worker_phone');
    }

    public function down()
    {
        $this->dropTable('{{%order_ext_worker}}');

        return true;
    }
}
