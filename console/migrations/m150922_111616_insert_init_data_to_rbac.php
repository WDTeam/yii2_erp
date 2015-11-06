<?php

use yii\db\Schema;
use yii\db\Migration;

class m150922_111616_insert_init_data_to_rbac extends Migration
{
    public function up()
    {
        \Yii::$app->db->createCommand("
            
            insert  into {{%auth_item}}(`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) values ('shopmanager/shop-manager/index',2,'家政公司管理列表页',NULL,NULL,NULL,NULL),('sidebar-customer',2,'顾客管理模块',NULL,NULL,NULL,NULL),('sidebar-finance',2,'财务管理模块',NULL,NULL,NULL,NULL),('sidebar-housekeep',2,'小家政管理模块',NULL,NULL,NULL,NULL),('sidebar-operation',2,'运营管理模块',NULL,NULL,NULL,NULL),('sidebar-order',2,'订单管理模块',NULL,NULL,NULL,NULL),('sidebar-payment',2,'左侧菜单栏-支付管理模块',NULL,NULL,NULL,NULL),('sidebar-pop',2,'POP管理模块',NULL,NULL,NULL,NULL),('sidebar-shop',2,'门店管理模块',NULL,NULL,NULL,NULL),('sidebar-supplier',2,'供应商管理',NULL,NULL,NULL,NULL),('sidebar-worker',2,'阿姨管理模块',NULL,NULL,NULL,NULL),('管理员',1,'',NULL,NULL,1442918467,1446101591);
            insert  into {{%auth_item_child}}(`parent`,`child`) values ('管理员','shopmanager/shop-manager/index'),('管理员','sidebar-customer'),('管理员','sidebar-finance'),('管理员','sidebar-housekeep'),('管理员','sidebar-operation'),('管理员','sidebar-order'),('管理员','sidebar-pop'),('管理员','sidebar-shop'),('管理员','sidebar-supplier'),('管理员','sidebar-worker'),('管理员','sidebar-payment');
            insert  into {{%auth_assignment}}(`item_name`,`user_id`,`created_at`) values ('管理员','1',1442919564),('管理员','2',1446135511),('管理员','3',1446135511),('管理员','4',1446135511);
            
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
