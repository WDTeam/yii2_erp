<?php

use yii\db\Schema;
use yii\db\Migration;

class m150919_102701_ejj_customer_address extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'用户地址表\'';
        }
        $this->createTable('{{%customer_address}}', [
            'id'=>  Schema::TYPE_PK.'(8) NOT NULL AUTO_INCREMENT COMMENT \'主键\'' ,
            'customer_id' => Schema::TYPE_INTEGER.'(8) NOT NULL COMMENT \'关联客户\'' ,
            'region_id'=>  Schema::TYPE_INTEGER.'(8) NOT NULL COMMENT \'关联区域\'' ,
            'customer_address_detail'=>  Schema::TYPE_STRING.'(64) NOT NULL COMMENT \'详细地址\'' ,
            'customer_address_status'=>  Schema::TYPE_SMALLINT.'(4) NOT NULL COMMENT \'1为默认地址，-1为非默认地址\'' ,
            'customer_address_longitude'=>  Schema::TYPE_DOUBLE.'(8,3) NULL DEFAULT NULL COMMENT \'经度\'' ,
            'customer_address_latitude'=>  Schema::TYPE_DOUBLE.'(8,3) NULL DEFAULT NULL COMMENT \'纬度\'' ,
            'created_at'=>  Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'创建时间\'' ,
            'updated_at'=>  Schema::TYPE_INTEGER.'(11) NOT NULL COMMENT \'更新时间\'' ,
            'is_del'=>  Schema::TYPE_SMALLINT.'(4) NULL DEFAULT NULL' ,
            ], $tableOptions);
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
