<?php

use yii\db\Schema;
use yii\db\Migration;

class m150924_093636_add_field_to_shop extends Migration
{
    public function up()
    {
        $this->execute("
        ALTER TABLE {{%shop}} ADD COLUMN `province_id` INT(11) NULL COMMENT '省份ID' AFTER `province_name`, ADD COLUMN `city_id` INT(11) NULL COMMENT '城市ID' AFTER `city_name`, ADD COLUMN `county_id` INT(11) NULL COMMENT '区县ID' AFTER `county_name`;
        ALTER TABLE {{%shop}} DROP COLUMN `province_name`, DROP COLUMN `city_name`, DROP COLUMN `county_name`;
        ");
    }

    public function down()
    {
        $this->execute("
        ALTER TABLE {{%shop}} DROP COLUMN `province_id`, DROP COLUMN `city_id`, DROP COLUMN `county_id`;
        ");
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
