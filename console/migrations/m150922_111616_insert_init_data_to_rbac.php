<?php

use yii\db\Schema;
use yii\db\Migration;

class m150922_111616_insert_init_data_to_rbac extends Migration
{
    public function up()
    {
        /**
         * 添加授权项
         */
//         $this->insert('{{%auth_item}}', [
//             'name'=>'sidebar_customer',
//             'type'=>2,
//             'description'=>'显示左侧菜单栏-顾客管理模块',
//         ]);
        /**
         * 添加角色
         */
        $this->insert('{{%auth_item}}', [
            'name'=>'system_group_super_admin',
            'type'=>1,
            'description'=>'超级管理员',
        ]);
        $this->insert('{{%auth_item}}', [
            'name'=>'system_group_admin',
            'type'=>1,
            'description'=>'管理员',
        ]);
        $this->insert('{{%auth_item}}', [
            'name'=>'group_developer_admin',
            'type'=>1,
            'description'=>'开发工程师管理员',
        ]);
        $this->insert('{{%auth_item}}', [
            'name'=>'group_tester_admin',
            'type'=>1,
            'description'=>'测试工程师管理员',
        ]);
        $this->insert('{{%auth_item}}', [
            'name'=>'group_tester',
            'type'=>1,
            'description'=>'测试工程师',
        ]);
        $this->insert('{{%auth_item}}', [
            'name'=>'group_shopmanager_admin',
            'type'=>1,
            'description'=>' 家政门店管理员',
        ]);
        $this->insert('{{%auth_item}}', [
            'name'=>'group_order_admin',
            'type'=>1,
            'description'=>'订单响应组管理员',
        ]);
        $this->insert('{{%auth_item}}', [
            'name'=>'group_order',
            'type'=>1,
            'description'=>'订单响应员',
        ]);
        $this->insert('{{%auth_item}}', [
            'name'=>'group_ordersort_admin',
            'type'=>1,
            'description'=>'订单派单组管理员',
        ]);
        $this->insert('{{%auth_item}}', [
            'name'=>'group_ordersort',
            'type'=>1,
            'description'=>'订单派单员',
        ]);
        $this->insert('{{%auth_item}}', [
            'name'=>'group_finance_admin',
            'type'=>1,
            'description'=>'财务结算管理员',
        ]);
        $this->insert('{{%auth_item}}', [
            'name'=>'group_finance',
            'type'=>1,
            'description'=>'财物结算员',
        ]);
        /**
         * 给角色授权
         */
        $this->insert('{{%auth_item_child}}', [
            'parent'=>'system_group_super_admin',
            'child'=>'system_group_admin',
        ]);
        /**
         * 给用户分配角色
         */
        $this->insert('{{%auth_assignment}}', [
            'item_name'=>'system_group_super_admin',
            'user_id'=>1,
        ]);
        $this->insert('{{%auth_assignment}}', [
            'item_name'=>'system_group_super_admin',
            'user_id'=>2,
        ]);
        $this->insert('{{%auth_assignment}}', [
            'item_name'=>'system_group_super_admin',
            'user_id'=>3,
        ]);
        $this->insert('{{%auth_assignment}}', [
            'item_name'=>'system_group_super_admin',
            'user_id'=>4,
        ]);
        $this->insert('{{%auth_assignment}}', [
            'item_name'=>'group_ordersort',
            'user_id'=>1000,
        ]);
        $this->insert('{{%auth_assignment}}', [
            'item_name'=>'group_ordersort',
            'user_id'=>1001,
        ]);
        $this->insert('{{%auth_assignment}}', [
            'item_name'=>'group_ordersort',
            'user_id'=>1002,
        ]);
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks=0;');
        $this->truncateTable('{{%auth_assignment}}');
        $this->truncateTable('{{%auth_item_child}}');
        $this->truncateTable('{{%auth_item}}');
        $this->execute('SET foreign_key_checks=1;');
        return true;
    }
}
