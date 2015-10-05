<?php

use yii\db\Schema;
use yii\db\Migration;

class m150919_101431_create_table_worker_device extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'阿姨设备信息表\'';
        }
        $this->createTable('{{%worker_device}}', [

            'worker_id' => Schema::TYPE_PK . '(11)  COMMENT \'阿姨Id\'',
            'worker_device_curr_lng' => Schema::TYPE_FLOAT . ' DEFAULT NULL COMMENT \'阿姨客户端当前经度\'',
            'worker_device_curr_lat' => Schema::TYPE_FLOAT . ' DEFAULT NULL COMMENT \'阿姨客户端当前纬度\'',
            'worker_device_client_version' => Schema::TYPE_STRING. '(20) DEFAULT NULL COMMENT \'\'',
            'worker_device_version_name' => Schema::TYPE_STRING . '(20) DEFAULT NULL COMMENT \'\'',
            'worker_device_token' => Schema::TYPE_STRING . '(100) DEFAULT NULL COMMENT \'阿姨客户端设备号\'',
            'worker_device_mac_addr' => Schema::TYPE_STRING . '(40) DEFAULT NULL COMMENT \'阿姨登录客户端mac地址\'',
            'worker_device_login_ip' => Schema::TYPE_STRING . '(20) DEFAULT NULL COMMENT \'阿姨最新登录ip地址\'',
            'worker_device_login_time' => Schema::TYPE_INTEGER . '(10) DEFAULT NULL COMMENT \'阿姨登录客户端最新时间\'',
            'created_ad' => Schema::TYPE_INTEGER . '(10) DEFAULT NULL COMMENT \'创建时间\'',
            'updated_ad' => Schema::TYPE_INTEGER . '(10) DEFAULT NULL COMMENT \'最后更新时间\'',
        ], $tableOptions);
    }

    public function down()
    {
      $this->dropTable('{{%worker_device}}');
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
