<?php

use yii\db\Schema;
use yii\db\Migration;

class m150926_035719_create_table_operation_advert_release extends Migration
{
    const TB_NAME = '{{%operation_advert_release}}';

    public function up()
    {
        $sql = 'DROP TABLE IF EXISTS ' . self::TB_NAME;
        $this->execute($sql);

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'广告发布表\'';
        }
        $this->createTable(self::TB_NAME, [
            'id' => Schema::TYPE_PK . ' AUTO_INCREMENT COMMENT \'编号\'' ,
            'advert_content_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'广告内容编号\'',
            'city_id' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'城市编号\'',
            'city_name' => Schema::TYPE_STRING . '(60) DEFAULT NULL COMMENT \'城市名称\'',
            'starttime' => Schema::TYPE_DATETIME. ' DEFAULT NULL COMMENT \'开始时间\'',
            'endtime' => Schema::TYPE_DATETIME. ' DEFAULT NULL COMMENT \'结束时间\'',
            'advert_release_order' => Schema::TYPE_SMALLINT. '(2) DEFAULT NULL COMMENT \'同城广告排序位置\'',
            'status' => Schema::TYPE_SMALLINT . '(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT \'是否上线\'',
            'is_softdel' => Schema::TYPE_SMALLINT . '(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT \'状态\'',
            'created_at' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'编辑时间\'',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable(self::TB_NAME);

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
