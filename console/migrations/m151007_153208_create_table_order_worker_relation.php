<?php

use yii\db\Schema;
use yii\db\Migration;

class m151007_153208_create_table_order_worker_relation extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions ='CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'订单阿姨关系表\'';
        }


        $this->createTable('{{%order_worker_relation}}', [
            'id'=> Schema::TYPE_BIGPK .'(20) NOT NULL AUTO_INCREMENT COMMENT \'编号\'',
            'created_at' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER.'(11) unsigned NOT NULL DEFAULT 0 COMMENT \'修改时间\'',
            'order_id' => Schema::TYPE_BIGINT . '(20) NOT NULL DEFAULT 0 COMMENT \'订单id\'',
            'worker_id' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT \'阿姨id\'',
            'order_worker_relation_memo' => Schema::TYPE_STRING . '(255) NOT NULL DEFAULT \'\' COMMENT \'订单阿姨备注\'',
            'order_worker_relation_status' => Schema::TYPE_STRING . '(255) NOT NULL DEFAULT \'\' COMMENT \'订单阿姨状态\'',
            'admin_id' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT 0 COMMENT \'派单员id\'',
            'isdel' => Schema::TYPE_BOOLEAN . '(1) unsigned NOT NULL DEFAULT 0 COMMENT \'是否已删除\'',
        ], $tableOptions);

        $this->createIndex('idx-order_worker_relation-order_id', '{{%order_worker_relation}}', 'order_id');
        $this->createIndex('idx-order_worker_relation-worker_id', '{{%order_worker_relation}}', 'worker_id');
        $this->createIndex('idx-order_worker_relation-isdel', '{{%order_worker_relation}}', 'isdel');
    }

    public function down()
    {
        $this->dropTable('{{%order_worker_relation}}');

        return true;
    }


}
