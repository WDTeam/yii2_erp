<?php

use yii\db\Schema;
use yii\db\Migration;

class m150922_111616_insert_init_data_to_rbac extends Migration
{
    public function up()
    {
        \Yii::$app->db->createCommand("
            insert  into {{%auth_item}}(`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) values ('area',2,'区域管理模块',NULL,NULL,NULL,NULL),('customer',2,'顾客管理模块',NULL,NULL,NULL,NULL),('finance',2,'财务管理模块',NULL,NULL,NULL,NULL),('housekeep',2,'小家政管理模块',NULL,NULL,NULL,NULL),('operation',2,'运营管理模块',NULL,NULL,NULL,NULL),('order',2,'订单管理模块',NULL,NULL,NULL,NULL),('pop',2,'POP管理模块',NULL,NULL,NULL,NULL),('shop',2,'门店管理模块',NULL,NULL,NULL,NULL),('worker',2,'阿姨管理模块',NULL,NULL,NULL,NULL),('管理员',1,'',NULL,NULL,1442918467,1442919546);
            insert  into {{%auth_item_child}}(`parent`,`child`) values ('管理员','area'),('管理员','customer'),('管理员','finance'),('管理员','housekeep'),('管理员','operation'),('管理员','order'),('管理员','pop'),('管理员','shop'),('管理员','worker');
            insert  into {{%auth_assignment}}(`item_name`,`user_id`,`created_at`) values ('管理员','1',1442919564);
        ")->execute();
    }

    public function down()
    {
        echo "m150922_111616_insert_init_data_to_rbac cannot be reverted.\n";

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
