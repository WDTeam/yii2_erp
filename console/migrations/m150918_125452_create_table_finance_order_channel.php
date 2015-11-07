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
            'finance_order_channel_name' => Schema::TYPE_STRING . '(50) DEFAULT NULL COMMENT \'渠道名称\'',
			'finance_order_channel_rate' => Schema::TYPE_STRING . '(6) DEFAULT NULL COMMENT \'比率\'',
            'finance_order_channel_sort' => Schema::TYPE_SMALLINT . '(5) DEFAULT 1 COMMENT \'支付显示\'',
            'finance_order_channel_is_lock' => Schema::TYPE_SMALLINT . '(1) DEFAULT \'1\' COMMENT \'下单显示\'',
			'finance_order_channel_source' => Schema::TYPE_SMALLINT . '(2) DEFAULT \'1\' COMMENT \'下单来源\'',
            'create_time' => Schema::TYPE_INTEGER . '(10) DEFAULT NULL COMMENT \'增加时间\'',
            'is_del' => Schema::TYPE_SMALLINT . '(1) DEFAULT \'0\' COMMENT \'0 正常 1 删除\'',
        ], $tableOptions);

        $this->execute("
			INSERT INTO {{%finance_order_channel}} VALUES ('3', 'APP百度钱包客户端', '1', '1', '2', '0','1443339882', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('7', 'H5百度直达号e家洁', '1', '1', '2','0','1443339882', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('8', '美团上门', '0.01', '1', '1', '0','1443339882', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('9', '点评到家', '1', '1', '1', '0','1443339882', '0');
		    INSERT INTO {{%finance_order_channel}} VALUES ('10', '京东到家', '1', '1', '1', '0','1443339882', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('11', '糯米网团购', '1', '1', '1', '0','1443339882', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('12', '大众点评团购', '1', '1', '1', '0','1443339882', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('13', '支付宝服务窗', '1', '1', '1', '0','1443339882', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('14', '淘宝店dudujiaoche', '1', '1', '2','0', '1443339882', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('15', '淘宝店e家洁家政公司', '1', '1', '2','0', '1443339882', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('16', '百度闭环3600行', '1', '1', '2', '0','1443339882', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('17', '百度订单分发手机百度', '1', '1', '1', '0','1443339882', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('18', 'APP到位', '1', '1', '1', '0','1443339882', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('19', '美团团购', '0.01', '1', '1', '0','1443339882', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('21', '新浪微博', '1', '1', '2', '0','1443339882', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('2',  '百度爱生活', '1', '1', '1', '0','1443339882', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('4',  '淘宝', '1', '1', '2', '0','1443339882', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('6',  '赶集', '1', '1', '1', '0','1443339882', '0');
            INSERT INTO {{%finance_order_channel}} VALUES ('1',  '58', '1', '1', '2', '0','1443339882', '1');
			INSERT INTO {{%finance_order_channel}} VALUES ('24', '拉手团购', '1', '1', '1', '0','1443339882', '0');
			INSERT INTO {{%finance_order_channel}} VALUES ('20', 'E家洁', '1', '1', '1', '0','1443339882', '0');"
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
