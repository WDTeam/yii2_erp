<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_195337_create_table_customer extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'客户表\'';
        }
        $this->createTable('{{%customer}}', [
            'id'=>  Schema::TYPE_PK.'(8) NOT NULL AUTO_INCREMENT COMMENT \'ID\'',
            'customer_name'=>  Schema::TYPE_STRING.'(16) COMMENT \'用户名\'',
            'customer_sex' => Schema::TYPE_SMALLINT.'(4) COMMENT \'性别\'',
            'customer_birth'=>  Schema::TYPE_INTEGER.'(11) DEFAULT NULL COMMENT \'生日\'',
            'customer_photo'=>  Schema::TYPE_STRING.'(32) DEFAULT NULL COMMENT \'头像\'',
            'customer_phone' => Schema::TYPE_STRING.'(11) UNIQUE COMMENT \'电话\'',
            'customer_email'=>  Schema::TYPE_STRING.'(32) DEFAULT NULL COMMENT \'邮箱\'',
            'operation_area_id'=>  Schema::TYPE_INTEGER.'(8) DEFAULT NULL COMMENT \'商圈\'',
			'operation_area_name'=> Schema::TYPE_STRING.'(255) DEFAULT NULL COMMENT \'商圈\'',
            'operation_city_id'=> Schema::TYPE_INTEGER.'(8) DEFAULT NULL COMMENT \'城市\'',
 			'operation_city_name'=> Schema::TYPE_STRING.'(255) DEFAULT NULL COMMENT \'城市\'',
            'customer_level'=>  Schema::TYPE_SMALLINT.'(4) DEFAULT NULL COMMENT \'评级\'',
            'customer_complaint_times'=>  Schema::TYPE_INTEGER.'(8) DEFAULT 0 COMMENT \'投诉\'',
            'customer_platform_version'=> Schema::TYPE_STRING.'(16) DEFAULT NULL COMMENT \'操作系统版本号\'',
            'customer_app_version'=> Schema::TYPE_STRING.'(16) DEFAULT NULL COMMENT \'app版本号\'',
            'customer_mac'=> Schema::TYPE_STRING.'(16) DEFAULT NULL COMMENT \'mac地址\'',
            'customer_login_ip'=> Schema::TYPE_STRING.'(16) DEFAULT NULL COMMENT \'登陆ip\'',
            'customer_login_time'=>  Schema::TYPE_INTEGER.'(11) DEFAULT NULL COMMENT \'登陆时间\'',
            'customer_is_vip'=>  Schema::TYPE_SMALLINT.'(4) DEFAULT NULL COMMENT \'身份\'',
            
            'customer_is_weixin'=>Schema::TYPE_SMALLINT.'(4) DEFAULT 0 COMMENT \'是否微信客户\'',
            'weixin_id'=>  Schema::TYPE_STRING.'(255) DEFAULT NULL COMMENT \'微信id\'',
            'created_at'=>  Schema::TYPE_INTEGER.'(11) COMMENT \'创建时间\'',
            'updated_at'=>  Schema::TYPE_INTEGER.'(11) COMMENT \'更新时间\'',
            'is_del'=>  Schema::TYPE_SMALLINT.'(4) DEFAULT NULL COMMENT \'加入黑名单\'',
            ], $tableOptions);

        // $this->batchInsert('{{%customer}}',
        //     ['id','customer_name','customer_sex','customer_birth','customer_photo','customer_phone','customer_email',
        //     'operation_area_id', 'operation_city_id', 'general_region_id', 'customer_live_address_detail',
        //      'customer_level', 'customer_complaint_times',
        //      'customer_src', 'channal_id', 'platform_id', 'customer_platform_version', 'customer_app_version', 'customer_mac',
        //      'customer_login_ip', 'customer_login_time', 'customer_is_vip',
        //      'created_at', 'updated_at', 'is_del', 'customer_del_reason'],
        //     [
        //         [1,'刘道强',1,time(), '', '18519654001', 'liuzhiqiang@corp.1jiajie.com',
        //         1, 1, 1, 'SOHO一期2单元908',
        //         1, 0,
        //         1, 1, 1, '5.0', '1.0', 'xxxxxxxxxxxxxxx',
        //         '192.168.0.1', time(), 1,
        //         time(), time(), 0, ''],
        //     ]);
    }

    public function down()
    {
        $this->dropTable('{{%customer}}');

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
