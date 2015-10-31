<?php

use yii\db\Schema;
use yii\db\Migration;

class m150918_125452_create_table_finance_order_channel extends Migration
{
    /**
     *
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'订单渠道表\'';
        }

        $this->createTable('{{%finance_order_channel}}', [
            'id' => Schema::TYPE_PK . '(5) AUTO_INCREMENT  COMMENT \'主键id\'',
            'pay_channel_id' => Schema::TYPE_SMALLINT . '(5) DEFAULT 0 COMMENT \'支付渠道ID\'',
            'finance_order_channel_name' => Schema::TYPE_STRING . '(50) DEFAULT NULL COMMENT \'渠道名称\'',
			'finance_order_channel_rate' => Schema::TYPE_STRING . '(6) DEFAULT NULL COMMENT \'比率\'',
            'finance_order_channel_sort' => Schema::TYPE_SMALLINT . '(5) DEFAULT 1 COMMENT \'支付显示\'',
            'finance_order_channel_is_lock' => Schema::TYPE_SMALLINT . '(1) DEFAULT \'1\' COMMENT \'下单显示\'',
            'create_time' => Schema::TYPE_INTEGER . '(10) DEFAULT NULL COMMENT \'增加时间\'',
            'is_del' => Schema::TYPE_SMALLINT . '(1) DEFAULT \'0\' COMMENT \'0 正常 1 删除\'',
        ], $tableOptions);

        $this->createIndex('pay_channel_id','{{%finance_order_channel}}','pay_channel_id');
        $this->execute(
			"INSERT INTO {{%finance_order_channel}} VALUES ('1', '11', 'APP微信客户端', '1', '1', '2', '1443339882', '1');
			INSERT INTO {{%finance_order_channel}} VALUES ('2', '10', 'H5手机微信', '1', '1', '1', '1443339882', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('3', '8', 'APP百度钱包客户端', '1', '1', '2', '1443339882', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('4', '12', 'APP银联客户端', '1', '1', '2', '1443339882', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('5', '6', 'APP支付宝客户端', '1', '1', '1', '1443339882', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('6', '6', 'WEB官网', '1', '1', '1', '0', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('7', '8', 'H5百度直达号e家洁', '1', '1', '2', '1443339882', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('8', '1', '美团上门', '0.01', '1', '1', '1443339882', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('9', '2', '点评到家', '1', '1', '1', '1443339882', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('10', '3', '京东到家', '1', '1', '1', '1443339882', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('11', '4', '糯米网团购', '1', '1', '1', '1443339882', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('12', '2', '大众点评团购', '1', '1', '1', '1443339882', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('13', '5', '支付宝服务窗', '1', '1', '1', '1443339882', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('14', '6', '淘宝店dudujiaoche', '1', '1', '2', '1443339882', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('15', '7', '淘宝店e家洁家政公司', '1', '1', '2', '1443339882', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('16', '8', '百度闭环3600行', '1', '1', '2', '1443339882', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('17', '8', '百度订单分发手机百度', '1', '1', '1', '1443339882', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('18', '9', 'APP到位', '1', '1', '1', '1443339882', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('19', '1', '美团团购', '0.01', '1', '1', '1443339882', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('20', '14', '后台下单', '1', '1', '1', '1443339882', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('21', '15', '新浪微博', '1', '1', '2', '1443339882', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('22', '2', '大众点评退款', '1', '2', '2', '1443339882', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('23', '10', '微信NAVITE', '1', '1', '1', '1443339882', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('24', '6', '支付宝WAP', '1', '1', '1', '1443339882', '0');"
        );
    }

    /**
     * @return bool
     */
    public function down()
    {
        $this->dropTable('{{%finance_order_channel}}');

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
