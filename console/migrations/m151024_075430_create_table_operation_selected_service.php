<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151024_075430_create_table_operation_selected_service extends Migration
{
    const TB_NAME = '{{%operation_selected_service}}';

    public function safeUp()
    {
        $sql = 'DROP TABLE IF EXISTS ' . self::TB_NAME;
        $this->execute($sql);

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'精品保洁\'';
        }

        $this->createTable(self::TB_NAME, [
            'id' => Schema::TYPE_PK . '(11) NOT NULL AUTO_INCREMENT COMMENT \'ID\'',
            'selected_service_goods_id' => Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT \'商品ID\'',
            'selected_service_scene' => Schema::TYPE_STRING . '(32) NOT NULL COMMENT \'场景\'',
            'selected_service_area' => Schema::TYPE_STRING . '(32) NOT NULL COMMENT \'区域\'',
            'selected_service_sub_area' => Schema::TYPE_STRING . '(64) NOT NULL COMMENT \'子区域\'',
            'selected_service_standard' => Schema::TYPE_STRING . '(128) NOT NULL COMMENT \'标准\'',
            'selected_service_area_standard' => Schema::TYPE_SMALLINT . '(1) unsigned NOT NULL DEFAULT 0 COMMENT \'面积标准,1:100平米以下;2:100平米以上\'',
            'selected_service_price' => 'decimal(10,2) NOT NULL COMMENT \'价格\'',
            'selected_service_unit' => Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT \'单位时间，分钟\'',
            'selected_service_photo' => Schema::TYPE_STRING . '(150)  DEFAULT \'\' COMMENT \'商品头像地址\'',
            'is_softdel' => Schema::TYPE_SMALLINT . '(1) unsigned NOT NULL DEFAULT 0 COMMENT \'状态\'',
            'created_at' => Schema::TYPE_INTEGER. '(11) DEFAULT NULL COMMENT \'创建时间\'',
            'updated_at' => Schema::TYPE_INTEGER . '(11) DEFAULT NULL COMMENT \'编辑时间\'',
            'remark' => Schema::TYPE_STRING . '(512) NOT NULL DEFAULT "" COMMENT \'备注\'',
        ], $tableOptions);

        $this->createIndex('selected_service_goods_id', self::TB_NAME, ['selected_service_goods_id'], false);
    }

    public function safeDown()
    {
        $this->dropTable(self::TB_NAME);
    }
}
