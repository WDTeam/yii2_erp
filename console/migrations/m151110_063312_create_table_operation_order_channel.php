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
		$this->createIndex('operation_order_channel_name','{{%operation_order_channel}}','operation_order_channel_name');
$this->execute("INSERT INTO {{%operation_order_channel}} VALUES ('1', 'android_user5.0.0', '1', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('3', 'Pcweb', '1', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('4', 'mobileweb', '1', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('5', '微信公众号', '1', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('6', '百度钱包app', '2', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('7', '百度直达号', '2', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('8', '美团上门', '2', '0.01', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('9', '淘宝店e家洁家政公司', '3', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('10', '京东到家', '2', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('11', '新浪微博轻应用', '2', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('12', '大众点评到家', '2', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('13', '百度闭环3600行', '2', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('14', '百度订单分发手机百度', '2', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('15', '支付宝服务窗', '2', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('16', '百度爱生活', '2', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('17', '淘宝', '2', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('18', '到位', '2', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('19', '美团团购', '3', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('20', '后台下单', '3', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('21', '淘宝店dudujiaoche', '3', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('22', '大众点评团购', '3', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('23', '糯米团购', '3', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('24', '赶集', '3', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('25', '58', '3', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('26', '拉手团购', '3', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('27', 'e家洁老系统', '3', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('28', 'ios_user5.0.0', '1', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('29', 'iOS4.0.3', '1', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('30', 'iOS4.2.2', '1', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('31', 'iOS4.2.2', '1', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('32', 'iOS4.1.1', '1', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('33', 'Android4.0.1', '1', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('34', 'Android4.2.3', '1', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('35', 'Android4.2.2', '1', '1', '1', 'admin', '1447148814', '0');
INSERT INTO {{%operation_order_channel}} VALUES ('36', 'Android4.2.1', '1', '1', '1', 'admin', '1447148814', '0');"
        );
    }


    public function down()
    {
        $this->dropTable("{{%operation_order_channel}}");
        return true;
    }
}
