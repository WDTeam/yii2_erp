<?php

use yii\db\Schema;
use yii\db\Migration;

class m151110_063312_create_table_operation_order_channel extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'订单渠道表\'';
        }
        $this->createTable('{{%operation_order_channel}}', [
            'id'=>  Schema::TYPE_PK.'(5) NOT NULL AUTO_INCREMENT COMMENT \'主键id\'',
            'operation_order_channel_name'=>  Schema::TYPE_STRING.'(50) DEFAULT NULL COMMENT \'订单渠道名称\'',
            'operation_order_channel_type' => Schema::TYPE_SMALLINT.'(1) DEFAULT 1 COMMENT \'订单渠道类别\'',
            'operation_order_channel_rate'=>  Schema::TYPE_STRING.'(6) DEFAULT NULL COMMENT \'比率\'',
            'system_user_id'=>  Schema::TYPE_SMALLINT.'(2) DEFAULT 0 COMMENT \'添加人id\'',
            'system_user_name'=>  Schema::TYPE_STRING.'(40) DEFAULT 0 COMMENT \'添加人名称\'',
            'create_time'=>  Schema::TYPE_INTEGER.'(10) DEFAULT NULL COMMENT \'增加时间\'',
            'is_del'=>  Schema::TYPE_SMALLINT.'(1) DEFAULT 0 COMMENT \'0 正常 1 删除\'',
            ], $tableOptions);
$this->execute("INSERT INTO {{%operation_order_channel}} VALUES ('1', 'Android(版本号)', '1', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('2', 'ios(版本号)', '1', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('3', 'Pcweb', '1', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('4', 'H5', '1', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('5', '微信', '1', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('6', '百度钱包app', '1', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('7', '百度直达号', '1', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('19', '美团团购', '3', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('12', '大众点评到家', '2', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('10', '京东到家', '2', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('11', '新浪微博轻应用', '1', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('9', '淘宝店e家洁家政公司', '3', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('13', '百度闭环3600行', '1', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('14', '百度订单分发手机百度', '1', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('18', '到位', '2', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('16', '百度爱生活', '2', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('17', '淘宝', '2', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('15', '支付宝服务窗', '1', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('8', '美团上门', '2', '0.01', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('20', '后台下单', '3', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('21', '淘宝店dudujiaoche', '3', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('22', '大众点评团购', '3', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('23', '糯米团购', '3', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('24', '赶集', '3', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('25', '58', '3', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('26', '拉手团购', '3', '1', '1', 'admin', '1447148814', '0');"
        );
    }


    public function down()
    {
        $this->dropTable("{{%operation_order_channel}}");
        return true;
    }
}
