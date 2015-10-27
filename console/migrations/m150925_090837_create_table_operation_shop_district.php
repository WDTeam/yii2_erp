<?php

use yii\db\Schema;
use yii\db\Migration;

class m150925_090837_create_table_operation_shop_district extends Migration
{
    public function up(){
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'城市商圈表\'';
        }
        $this->createTable('{{%operation_shop_district}}', [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT COMMENT \'编号\'' ,
            'operation_shop_district_name' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'商圈名称\'',
            'operation_city_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'城市编号\'',
            'operation_city_name' => Schema::TYPE_STRING . '(50) DEFAULT NULL COMMENT \'城市名称\'',

            'operation_area_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'区域id\'',
            'operation_area_name' => Schema::TYPE_STRING . '(50) DEFAULT NULL COMMENT \'区域名称\'',

            'operation_shop_district_status' => Schema::TYPE_INTEGER . '(1) DEFAULT 1 COMMENT \'是否已上线(1: 未上线 2: 已上线)\'',
            'is_softdel' => Schema::TYPE_SMALLINT . '(1) unsigned NOT NULL DEFAULT 1 COMMENT \'状态\'',
            'created_at' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'编辑时间\'',
        ], $tableOptions);
        
        $this->execute(
            "insert into {{%operation_shop_district}} (`id`, `operation_shop_district_name`, `operation_city_id`, `operation_city_name`, `operation_area_id`, `operation_area_name`, `operation_shop_district_status`, `created_at`, `updated_at`) values
	        ('1','河西','120100','天津市','120103','河西区','1','1444372511','1444889634'),
	        ('2','和平区','120100','天津市','120101','和平区','1','1444372601','1444801388'),
                ('3','东城','110100',NULL,'110101','东城区','1','1444383355','1444383355'),
                ('4','西城区','110100',NULL,'110102','西城区','1','1444383375','1444383375'),
                ('5','朝阳','110100',NULL,'110105','朝阳区','1','1444383396','1444383396'),
                ('11','红桥区','120100','天津市','120106','红桥区','1','1444809127','1444809150'),
                ('12','武清区','120100','天津市','120114','武清区','1','1444809339','1444809339');"
        );
    }

    public function down(){
        $this->dropTable('{{%operation_shop_district}}');

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
