<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m150926_145516_create_table_shop_status extends Migration
{
    public function safeUp()
    {
        $this->execute("
            CREATE TABLE IF NOT EXISTS {{%shop_status}} (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `status_number` int(11) DEFAULT '0' COMMENT '状态码',
              `cause` varchar(255) DEFAULT NULL COMMENT '原因',
              `created_at` int(11) DEFAULT NULL COMMENT '生成时间',
              `model_name` varchar(50) NOT NULL DEFAULT '1' COMMENT '对应模型：1 Shop,2 ShopManager',
              `model_id` int(11) DEFAULT NULL COMMENT '对应模型ID',
              `status_type` int(3) NOT NULL DEFAULT '1' COMMENT '状态类型：1审核，2黑名单',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8 COMMENT='shop相关状态记录'

        ");
    }

    public function safeDown()
    {
        $this->dropTable('{{%shop_status}}');

        return true;
    }
}
