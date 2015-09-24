<?php

use yii\db\Schema;
use yii\db\Migration;

class m150924_093647_add_field_to_shopmanager extends Migration
{
public function up()
    {
        $this->execute("
        ALTER TABLE {{%shop_manager}} ADD COLUMN `province_id` INT(8) NULL COMMENT '省份ID' AFTER `province_name`, ADD COLUMN `city_id` INT(8) NULL COMMENT '城市ID' AFTER `city_name`, CHANGE `county_name` `county_name` VARCHAR(50) CHARSET utf8 COLLATE utf8_general_ci NULL COMMENT '区县', ADD COLUMN `county_id` INT(8) NULL COMMENT '区县ID' AFTER `county_name`;     
        ");
    }

    public function down()
    {
        $this->execute("
        ALTER TABLE {{%shop_manager}} DROP COLUMN `province_id`, DROP COLUMN `city_id`, DROP COLUMN `county_id`;     
        ");
        return false;
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
