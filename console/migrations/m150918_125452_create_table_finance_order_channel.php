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
            'finance_order_channel_sort' => Schema::TYPE_SMALLINT . '(5) DEFAULT 1 COMMENT \'排序\'',
            'finance_order_channel_is_lock' => Schema::TYPE_SMALLINT . '(1) DEFAULT \'1\' COMMENT \'1 上架 2 下架\'',
            'create_time' => Schema::TYPE_INTEGER . '(10) DEFAULT NULL COMMENT \'增加时间\'',
            'is_del' => Schema::TYPE_SMALLINT . '(1) DEFAULT \'0\' COMMENT \'0 正常 1 删除\'',

        ], $tableOptions);

        $this->execute(
            "INSERT INTO {{%finance_order_channel}} VALUES ('1', 'APP微信（客户端）', 1, 1, 0, '0');
             INSERT INTO {{%finance_order_channel}} VALUES ('2', 'H5手机微信', 2, 1, 0, '0');
             INSERT INTO {{%finance_order_channel}} VALUES ('3', 'APP百度钱包（客户端）', 3, 1, 0, '0');
             INSERT INTO {{%finance_order_channel}} VALUES ('4', 'APP银联（客户端）', 4, 1, 0, '0');
             INSERT INTO {{%finance_order_channel}} VALUES ('5', 'APP支付宝（客户端）', 5, 1, 0, '0');
             INSERT INTO {{%finance_order_channel}} VALUES ('6', 'WEB官网', 6, 1, 0, '0');
             INSERT INTO {{%finance_order_channel}} VALUES ('7', 'H5百度直达号（@e家洁）', 7, 1, 0, '0');
             INSERT INTO {{%finance_order_channel}} VALUES ('8', '美团上门', 8, 1, 0, '0');
             INSERT INTO {{%finance_order_channel}} VALUES ('9', '点评到家', 9, 1, 0, '0');
             INSERT INTO {{%finance_order_channel}} VALUES ('10', '京东到家', 10, 1, 0, '0');
             INSERT INTO {{%finance_order_channel}} VALUES ('11', '糯米网团购', 11, 1, 0, '0');
             INSERT INTO {{%finance_order_channel}} VALUES ('12', '大众点评团购', 12, 1, 0, '0');
             INSERT INTO {{%finance_order_channel}} VALUES ('13', '支付宝服务窗', 13, 1, 0, '0');
             INSERT INTO {{%finance_order_channel}} VALUES ('14', '淘宝店（dudujiaoche）', 14, 1, 0, '0');
             INSERT INTO {{%finance_order_channel}} VALUES ('15', '淘宝店（e家洁家政公司）', 15, 1, 0, '0');
             INSERT INTO {{%finance_order_channel}} VALUES ('16', '百度闭环（3600行）', null, 16, 1, 0, '0');
             INSERT INTO {{%finance_order_channel}} VALUES ('17', '百度订单分发（手机百度）', 17, 1, 0, '0');
             INSERT INTO {{%finance_order_channel}} VALUES ('18', 'APP到位', 18, 1, 0, '0');"
        );

    }

    /**
     * @return bool
     */
    public function down()
    {
        $this->dropTable('{{%finance_order_channel}}');

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
