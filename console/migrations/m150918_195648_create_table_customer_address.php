<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_195648_create_table_customer_address extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'用户地址表\'';
        }
        $this->createTable('{{%customer_address}}', [
            'id'=> Schema::TYPE_PK.'(8) NOT NULL AUTO_INCREMENT COMMENT \'主键\'',
            'customer_id' => Schema::TYPE_INTEGER.'(8) NOT NULL COMMENT \'关联客户\'',
            'general_region_id'=>  Schema::TYPE_INTEGER.'(8) NOT NULL COMMENT \'关联区域\'',
            'customer_address_detail'=>  Schema::TYPE_STRING.'(64) NOT NULL COMMENT \'详细地址\'',
            'customer_address_status'=>  Schema::TYPE_SMALLINT.'(4) NOT NULL COMMENT \'客户地址类型,1为默认地址，-1为非默认地址\'',
            'customer_address_longitude'=>  Schema::TYPE_DOUBLE.'(8,3) DEFAULT NULL COMMENT \'经度\'',
            'customer_address_latitude'=>  Schema::TYPE_DOUBLE.'(8,3) DEFAULT NULL COMMENT \'纬度\'',
            'customer_address_nickname'=>Schema::TYPE_STRING.'(32) NOT NULL COMMENT \'被服务者昵称\'',
            'customer_address_phone'=>Schema::TYPE_STRING.'(11) NOT NULL COMMENT \'被服务者手机\'',
            'created_at'=>  Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'创建时间\'',
            'updated_at'=>  Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'更新时间\'',
            'is_del'=>  Schema::TYPE_SMALLINT.'(4) DEFAULT 0 COMMENT \'是否逻辑删除\'',
            ], $tableOptions);

        $this->batchInsert('{{%customer_address}}',
            ['id','customer_id','general_region_id','customer_address_detail','customer_address_status','customer_address_longitude','customer_address_latitude', 'customer_address_nickname', 'customer_address_phone','created_at', 'updated_at', 'is_del'],
            [
                [1,1,1,'北京市朝阳区SOHO1',1, 12.888, 888.334, '测试昵称', '13554699534', time(),time(), 0],
                [2,1,1,'北京市朝阳区SOHO2',0, 12.888, 888.334, '测试昵称', '13554699534', time(),time(), 0],
                [3,1,1,'北京市朝阳区SOHO3',0, 12.888, 888.334, '测试昵称', '13554699534', time(),time(), 0],
            ]);
    }

    public function down()
    {
        $this->dropTable('{{%customer_address}}');
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
