<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_031530_create_table_operation_city extends Migration
{
    public function up()
    {
        $sql = 'DROP TABLE IF EXISTS {{%operation_city}}';
        $this->execute($sql);
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'开通城市表\'';
        }
        $this->createTable('{{%operation_city}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT  COMMENT \'编号\'' ,
            'province_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'省份编号\'',
            'province_name' => Schema::TYPE_STRING . '(30) DEFAULT NULL COMMENT \'省份名称\'',
            'city_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'城市编号\'',
            'city_name' => Schema::TYPE_STRING . '(50) DEFAULT NULL COMMENT \'城市名称\'',
            'operation_city_is_online' => Schema::TYPE_SMALLINT . '(1) DEFAULT 2 COMMENT \'城市是否上线（1为上线，2为下线）\'',
            'is_softdel' => Schema::TYPE_SMALLINT . '(1) unsigned NOT NULL DEFAULT 1 COMMENT \'状态\'',
            'created_at' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'编辑时间\'',
        ], $tableOptions);
        $this->execute(
            "insert into {{%operation_city}} (`id`, `province_id`, `province_name`, `city_id`, `city_name`, `operation_city_is_online`, `created_at`, `updated_at`) values
	        ('1','120000','天津','120100','天津市','1','1444283773','1444283773'),
	        ('2','110000','北京','110100','北京市','1','1444368462','1444368462'),
                ('3','140000','山西省','140300','阳泉市','1','1444413962','1444413962'),
                ('4','140000','山西省','140100','太原市','1','1444635891','1444635891'),
                ('5','230000','黑龙江省','231100','黑河市','2','1444816358','1444816358');"
        );
        
    }

    public function down()
    {
        $this->dropTable('{{%operation_city}}');

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
