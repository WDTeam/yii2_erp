<?php

use yii\db\Schema;
use yii\db\Migration;

class m150923_141027_create_table_finance_header extends Migration
{
    public function up()
     {
    	if ($this->db->driverName === 'mysql') {

			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB COMMENT=\'第三方表头记录表\'';

			}

			$this->createTable('{{%finance_header}}', [
	'id' => Schema::TYPE_PK . '(10) AUTO_INCREMENT COMMENT \'主键\'' ,
	'finance_header_key' => Schema::TYPE_SMALLINT . '(2) DEFAULT NULL COMMENT \'对应栏位\'' ,
	'finance_header_title' => Schema::TYPE_STRING . '(100) DEFAULT NULL COMMENT \'当前名称\'' ,
  'finance_header_name' => Schema::TYPE_STRING . '(100) DEFAULT NULL COMMENT \'表头名称\'' ,
	'finance_order_channel_id' => Schema::TYPE_SMALLINT . '(4) DEFAULT NULL COMMENT \'订单渠道id\'' ,
	'finance_order_channel_name' => Schema::TYPE_STRING . '(100) DEFAULT NULL COMMENT \'订单渠道名称\'' ,
	'finance_pay_channel_id' => Schema::TYPE_SMALLINT . '(4) DEFAULT NULL COMMENT \'支付渠道id\'' ,
	'finance_pay_channel_name' => Schema::TYPE_STRING . '(100) DEFAULT NULL COMMENT \'支付渠道名称\'' ,
    'finance_header_where' => Schema::TYPE_STRING . '(100) DEFAULT NULL COMMENT \'比对字段名称\'' ,
	'create_time' => Schema::TYPE_INTEGER. '(10) DEFAULT NULL COMMENT \'创建时间\'' ,
	'is_del' => Schema::TYPE_SMALLINT . '(1) DEFAULT \'0\' COMMENT \'0 正常 1 删除\'' ,
				 ], $tableOptions);
    $this->createIndex('finance_order_channel_id','{{%finance_header}}','finance_order_channel_id');
        $this->execute(
			"INSERT INTO {{%finance_header}} VALUES ('70', '0', '美团上门', 'orderId', '8', '美团上门', '1', '', 'order_channel_order_num', '1445571406', '0');
INSERT INTO {{%finance_header}} VALUES ('71', '1', '美团上门', 'partnerId', '8', '美团上门', '1', null, null, '1445571406', '0');
INSERT INTO {{%finance_header}} VALUES ('72', '2', '美团上门', '销售价格', '8', '美团上门', '1', '', 'order_money', '1445571406', '0');
INSERT INTO {{%finance_header}} VALUES ('73', '3', '美团上门', '赠品-美团优惠', '8', '美团上门', '1', null, null, '1445571406', '0');
INSERT INTO {{%finance_header}} VALUES ('74', '4', '美团上门', '立减-商家优惠', '8', '美团上门', '1', '', 'order_channel_promote', '1445571406', '0');
INSERT INTO {{%finance_header}} VALUES ('75', '5', '美团上门', '券-商家优惠', '8', '美团上门', '1', '', 'order_channel_promote', '1445571406', '0');
INSERT INTO {{%finance_header}} VALUES ('76', '6', '美团上门', '折扣-商家优惠', '8', '美团上门', '1', '', 'order_channel_promote', '1445571406', '0');
INSERT INTO {{%finance_header}} VALUES ('77', '7', '美团上门', '一口价-商家优惠', '8', '美团上门', '1', '', 'order_channel_promote', '1445571406', '0');
INSERT INTO {{%finance_header}} VALUES ('78', '8', '美团上门', '退款-商家承担', '8', '美团上门', '1', '', 'refund', '1445571406', '0');
INSERT INTO {{%finance_header}} VALUES ('79', '9', '美团上门', 'type', '8', '美团上门', '1', '', 'function_way', '1445571406', '0');
INSERT INTO {{%finance_header}} VALUES ('80', '0', '百度钱包', '订单号', '0', '', '8', '百度钱包', 'order_channel_order_num', '1445572191', '0');
INSERT INTO {{%finance_header}} VALUES ('81', '1', '百度钱包', '百付宝交易号', '0', null, '8', '百度钱包', null, '1445572191', '0');
INSERT INTO {{%finance_header}} VALUES ('82', '2', '百度钱包', '创建时间', '0', null, '8', '百度钱包', null, '1445572191', '0');
INSERT INTO {{%finance_header}} VALUES ('83', '3', '百度钱包', '收入金额（+元）', '0', '', '8', '百度钱包', 'order_money', '1445572191', '0');
INSERT INTO {{%finance_header}} VALUES ('84', '4', '百度钱包', '支出金额（-元）', '0', '', '8', '百度钱包', 'refund', '1445572191', '0');
INSERT INTO {{%finance_header}} VALUES ('85', '5', '百度钱包', '业务类型', '0', null, '8', '百度钱包', null, '1445572191', '0');
INSERT INTO {{%finance_header}} VALUES ('86', '6', '百度钱包', '账户余额（元）', '0', null, '8', '百度钱包', null, '1445572191', '0');
INSERT INTO {{%finance_header}} VALUES ('87', '7', '百度钱包', '说明', '0', null, '8', '百度钱包', null, '1445572191', '0');
INSERT INTO {{%finance_header}} VALUES ('88', '0', '支付宝', '账务流水号', '0', null, '7', '支付宝1jjtb', null, '1445578905', '0');
INSERT INTO {{%finance_header}} VALUES ('89', '1', '支付宝', '业务流水号', '0', null, '7', '支付宝1jjtb', null, '1445578905', '0');
INSERT INTO {{%finance_header}} VALUES ('90', '2', '支付宝', '商户订单号', '0', '', '7', '支付宝1jjtb', 'order_channel_order_num', '1445578905', '0');
INSERT INTO {{%finance_header}} VALUES ('91', '3', '支付宝', '商品名称', '0', null, '7', '支付宝1jjtb', null, '1445578905', '0');
INSERT INTO {{%finance_header}} VALUES ('92', '4', '支付宝', '发生时间', '0', null, '7', '支付宝1jjtb', null, '1445578905', '0');
INSERT INTO {{%finance_header}} VALUES ('93', '5', '支付宝', '对方账号', '0', null, '7', '支付宝1jjtb', null, '1445578905', '0');
INSERT INTO {{%finance_header}} VALUES ('94', '6', '支付宝', '收入金额（+元）', '0', '', '7', '支付宝1jjtb', 'order_money', '1445578905', '0');
INSERT INTO {{%finance_header}} VALUES ('95', '7', '支付宝', '支出金额（-元）', '0', '', '7', '支付宝1jjtb', 'refund', '1445578905', '0');
INSERT INTO {{%finance_header}} VALUES ('96', '8', '支付宝', '账户余额（元）', '0', null, '7', '支付宝1jjtb', null, '1445578905', '0');
INSERT INTO {{%finance_header}} VALUES ('97', '9', '支付宝', '交易渠道', '0', null, '7', '支付宝1jjtb', null, '1445578905', '0');
INSERT INTO {{%finance_header}} VALUES ('98', '10', '支付宝', '业务类型', '0', null, '7', '支付宝1jjtb', null, '1445578905', '0');
INSERT INTO {{%finance_header}} VALUES ('99', '11', '支付宝', '备注', '0', null, '7', '支付宝1jjtb', null, '1445578905', '0');
INSERT INTO {{%finance_header}} VALUES ('100', '12', '支付宝', '项目', '0', null, '7', '支付宝1jjtb', null, '1445578905', '0');
INSERT INTO {{%finance_header}} VALUES ('101', '0', '微信后台', '交易时间', '0', null, '10', '微信后台', null, '1445580367', '0');
INSERT INTO {{%finance_header}} VALUES ('102', '1', '微信后台', '财付通订单号', '0', null, '10', '微信后台', null, '1445580367', '0');
INSERT INTO {{%finance_header}} VALUES ('103', '2', '微信后台', '商家订单号', '0', '', '10', '微信后台', 'order_channel_order_num', '1445580367', '0');
INSERT INTO {{%finance_header}} VALUES ('104', '3', '微信后台', '支付类型', '0', null, '10', '微信后台', null, '1445580367', '0');
INSERT INTO {{%finance_header}} VALUES ('105', '4', '微信后台', '银行订单号', '0', null, '10', '微信后台', null, '1445580367', '0');
INSERT INTO {{%finance_header}} VALUES ('106', '5', '微信后台', '交易状态', '0', null, '10', '微信后台', null, '1445580367', '0');
INSERT INTO {{%finance_header}} VALUES ('107', '6', '微信后台', '订单交易金额', '0', '', '10', '微信后台', 'order_money', '1445580367', '0');
INSERT INTO {{%finance_header}} VALUES ('108', '7', '微信后台', '退款单号', '0', null, '10', '微信后台', null, '1445580367', '0');
INSERT INTO {{%finance_header}} VALUES ('109', '8', '微信后台', '退款金额', '0', '', '10', '微信后台', 'refund', '1445580367', '0');
INSERT INTO {{%finance_header}} VALUES ('110', '9', '微信后台', '退款状态', '0', null, '10', '微信后台', null, '1445580367', '0');
INSERT INTO {{%finance_header}} VALUES ('111', '10', '微信后台', '手续费金额', '0', '', '10', '微信后台', 'decrease', '1445580367', '0');
INSERT INTO {{%finance_header}} VALUES ('112', '11', '微信后台', '费率', '0', null, '10', '微信后台', null, '1445580367', '0');
INSERT INTO {{%finance_header}} VALUES ('113', '12', '微信后台', '交易说明', '0', null, '10', '微信后台', null, '1445580367', '0');
INSERT INTO {{%finance_header}} VALUES ('114', '0', '银联后台', '交易流水号', '0', '', '12', '银联后台', 'order_channel_order_num', '1445583267', '0');
INSERT INTO {{%finance_header}} VALUES ('115', '1', '银联后台', '订单号', '0', null, '12', '银联后台', null, '1445583267', '0');
INSERT INTO {{%finance_header}} VALUES ('116', '2', '银联后台', '交易时间', '0', null, '12', '银联后台', null, '1445583267', '0');
INSERT INTO {{%finance_header}} VALUES ('117', '3', '银联后台', '交易类型', '0', null, '12', '银联后台', null, '1445583267', '0');
INSERT INTO {{%finance_header}} VALUES ('118', '4', '银联后台', '交易子类型', '0', null, '12', '银联后台', null, '1445583267', '0');
INSERT INTO {{%finance_header}} VALUES ('119', '5', '银联后台', '交易金额', '0', '', '12', '银联后台', 'order_money', '1445583267', '0');
INSERT INTO {{%finance_header}} VALUES ('120', '6', '银联后台', '支付方式', '0', null, '12', '银联后台', null, '1445583267', '0');
INSERT INTO {{%finance_header}} VALUES ('121', '7', '银联后台', '交易状态', '0', null, '12', '银联后台', null, '1445583267', '0');
INSERT INTO {{%finance_header}} VALUES ('122', '8', '银联后台', '响应码', '0', '', '12', '银联后台', 'function_way', '1445583267', '0');
INSERT INTO {{%finance_header}} VALUES ('123', '9', '银联后台', '系统跟踪号', '0', null, '12', '银联后台', null, '1445583267', '0');
INSERT INTO {{%finance_header}} VALUES ('124', '10', '银联后台', '系统时间', '0', null, '12', '银联后台', null, '1445583267', '0');
INSERT INTO {{%finance_header}} VALUES ('125', '11', '银联后台', '清算日期', '0', null, '12', '银联后台', null, '1445583267', '0');
INSERT INTO {{%finance_header}} VALUES ('126', '12', '银联后台', '清算金额', '0', null, '12', '银联后台', null, '1445583267', '0');
INSERT INTO {{%finance_header}} VALUES ('127', '13', '银联后台', '最后更新时间', '0', null, '12', '银联后台', null, '1445583267', '0');
INSERT INTO {{%finance_header}} VALUES ('128', '14', '银联后台', '账号', '0', null, '12', '银联后台', null, '1445583267', '0');
INSERT INTO {{%finance_header}} VALUES ('129', '15', '银联后台', '原交易订单号', '0', null, '12', '银联后台', null, '1445583267', '0');
INSERT INTO {{%finance_header}} VALUES ('130', '16', '银联后台', '累计退货金额', '0', null, '12', '银联后台', null, '1445583267', '0');
INSERT INTO {{%finance_header}} VALUES ('131', '0', '京东到家', '订单ID', '10', '京东到家', '1', '', 'order_channel_order_num', '1445586188', '0');
INSERT INTO {{%finance_header}} VALUES ('132', '1', '京东到家', '门店名称', '10', '京东到家', '1', null, null, '1445586188', '0');
INSERT INTO {{%finance_header}} VALUES ('133', '2', '京东到家', '门店编号', '10', '京东到家', '1', null, null, '1445586188', '0');
INSERT INTO {{%finance_header}} VALUES ('134', '3', '京东到家', '订单状态', '10', '京东到家', '1', null, null, '1445586188', '0');
INSERT INTO {{%finance_header}} VALUES ('135', '4', '京东到家', '商品金额', '10', '京东到家', '1', '', 'order_money', '1445586188', '0');
INSERT INTO {{%finance_header}} VALUES ('136', '5', '京东到家', '商品优惠', '10', '京东到家', '1', '', 'order_channel_promote', '1445586188', '0');
INSERT INTO {{%finance_header}} VALUES ('137', '6', '京东到家', '运费优惠', '10', '京东到家', '1', null, null, '1445586188', '0');
INSERT INTO {{%finance_header}} VALUES ('138', '7', '京东到家', '货款结算金额', '10', '京东到家', '1', null, null, '1445586188', '0');
INSERT INTO {{%finance_header}} VALUES ('139', '8', '京东到家', '运费', '10', '京东到家', '1', null, null, '1445586188', '0');
INSERT INTO {{%finance_header}} VALUES ('140', '9', '京东到家', '扣点金额', '10', '京东到家', '1', null, null, '1445586188', '0');
INSERT INTO {{%finance_header}} VALUES ('141', '10', '京东到家', '承运商', '10', '京东到家', '1', null, null, '1445586188', '0');
INSERT INTO {{%finance_header}} VALUES ('142', '11', '京东到家', '下单时间', '10', '京东到家', '1', null, null, '1445586188', '0');
INSERT INTO {{%finance_header}} VALUES ('143', '12', '京东到家', '妥投时间', '10', '京东到家', '1', null, null, '1445586188', '0');
INSERT INTO {{%finance_header}} VALUES ('144', '13', '京东到家', '支付方式', '10', '京东到家', '1', null, null, '1445586188', '0');
INSERT INTO {{%finance_header}} VALUES ('145', '0', '大众点评成功', '点评orderid', '12', '大众点评团购', '1', null, null, '1445698837', '0');
INSERT INTO {{%finance_header}} VALUES ('146', '1', '大众点评成功', 'e家洁订单', '12', '大众点评团购', '1', null, null, '1445698837', '0');
INSERT INTO {{%finance_header}} VALUES ('147', '2', '大众点评成功', '第三方ID', '12', '大众点评团购', '1', null, null, '1445698837', '0');
INSERT INTO {{%finance_header}} VALUES ('148', '3', '大众点评成功', '第三方orderid', '12', '大众点评团购', '1', '', 'order_channel_order_num', '1445698837', '0');
INSERT INTO {{%finance_header}} VALUES ('149', '4', '大众点评成功', '单价', '12', '大众点评团购', '1', null, null, '1445698837', '0');
INSERT INTO {{%finance_header}} VALUES ('150', '5', '大众点评成功', '数量', '12', '大众点评团购', '1', null, null, '1445698837', '0');
INSERT INTO {{%finance_header}} VALUES ('151', '6', '大众点评成功', '原总价', '12', '大众点评团购', '1', '', 'order_money', '1445698837', '0');
INSERT INTO {{%finance_header}} VALUES ('152', '7', '大众点评成功', '支付金额', '12', '大众点评团购', '1', '', '0', '1445698837', '0');
INSERT INTO {{%finance_header}} VALUES ('153', '8', '大众点评成功', 'phone', '12', '大众点评团购', '1', null, null, '1445698837', '0');
INSERT INTO {{%finance_header}} VALUES ('154', '9', '大众点评成功', 'productid', '12', '大众点评团购', '1', null, null, '1445698837', '0');
INSERT INTO {{%finance_header}} VALUES ('155', '10', '大众点评成功', '服务完成时间', '12', '大众点评团购', '1', null, null, '1445698837', '0');
INSERT INTO {{%finance_header}} VALUES ('156', '11', '大众点评成功', '预约时间', '12', '大众点评团购', '1', null, null, '1445698837', '0');
INSERT INTO {{%finance_header}} VALUES ('157', '12', '大众点评成功', '点评立减金额', '12', '大众点评团购', '1', null, null, '1445698837', '0');
INSERT INTO {{%finance_header}} VALUES ('158', '13', '大众点评成功', '点评优惠券金额', '12', '大众点评团购', '1', null, null, '1445698837', '0');
INSERT INTO {{%finance_header}} VALUES ('159', '14', '大众点评成功', '商户优惠金额', '12', '大众点评团购', '1', '', 'order_channel_promote', '1445698837', '0');
INSERT INTO {{%finance_header}} VALUES ('160', '15', '大众点评成功', '结算金额/成本', '12', '大众点评团购', '1', null, null, '1445698837', '0');
INSERT INTO {{%finance_header}} VALUES ('161', '0', '大众点评退款', '点评orderid', '22', '大众点评退款', '1', null, null, '1445838937', '0');
INSERT INTO {{%finance_header}} VALUES ('162', '1', '大众点评退款', 'e家洁订单', '22', '大众点评退款', '1', null, null, '1445838937', '0');
INSERT INTO {{%finance_header}} VALUES ('163', '2', '大众点评退款', '商户ID', '22', '大众点评退款', '1', null, null, '1445838937', '0');
INSERT INTO {{%finance_header}} VALUES ('164', '3', '大众点评退款', '第三方orderid', '22', '大众点评退款', '1', '', 'order_channel_order_num', '1445838937', '0');
INSERT INTO {{%finance_header}} VALUES ('165', '4', '大众点评退款', '单价', '22', '大众点评退款', '1', null, null, '1445838937', '0');
INSERT INTO {{%finance_header}} VALUES ('166', '5', '大众点评退款', '数量', '22', '大众点评退款', '1', null, null, '1445838937', '0');
INSERT INTO {{%finance_header}} VALUES ('167', '6', '大众点评退款', '总金额', '22', '大众点评退款', '1', '', 'order_money', '1445838937', '0');
INSERT INTO {{%finance_header}} VALUES ('168', '7', '大众点评退款', '支付金额', '22', '大众点评退款', '1', null, null, '1445838937', '0');
INSERT INTO {{%finance_header}} VALUES ('169', '8', '大众点评退款', '退款金额', '22', '大众点评退款', '1', null, null, '1445838937', '0');
INSERT INTO {{%finance_header}} VALUES ('170', '9', '大众点评退款', 'DP优惠券退款', '22', '大众点评退款', '1', null, null, '1445838937', '0');
INSERT INTO {{%finance_header}} VALUES ('171', '10', '大众点评退款', '立减退款', '22', '大众点评退款', '1', '', 'order_channel_promote', '1445838937', '0');
INSERT INTO {{%finance_header}} VALUES ('172', '11', '大众点评退款', 'phone', '22', '大众点评退款', '1', null, null, '1445838937', '0');
INSERT INTO {{%finance_header}} VALUES ('173', '12', '大众点评退款', 'productid', '22', '大众点评退款', '1', null, null, '1445838937', '0');
INSERT INTO {{%finance_header}} VALUES ('174', '13', '大众点评退款', '退款时间', '22', '大众点评退款', '1', null, null, '1445838937', '0');
INSERT INTO {{%finance_header}} VALUES ('175', '14', '大众点评退款', '预约时间', '22', '大众点评退款', '1', null, null, '1445838937', '0');
INSERT INTO {{%finance_header}} VALUES ('176', '15', '大众点评退款', '结算金额', '22', '大众点评退款', '1', null, null, '1445838937', '0');
INSERT INTO {{%finance_header}} VALUES ('177', '16', '大众点评退款', '全额退款0/半额退款1', '22', '大众点评退款', '1', '', 'function_way', '1445838937', '0');
INSERT INTO {{%finance_header}} VALUES ('178', '0', '到位', '订单号', '18', 'APP到位', '1', '', 'order_channel_order_num', '1445843174', '0');
INSERT INTO {{%finance_header}} VALUES ('179', '1', '到位', '店铺', '18', 'APP到位', '1', null, null, '1445843174', '0');
INSERT INTO {{%finance_header}} VALUES ('180', '2', '到位', '用户', '18', 'APP到位', '1', null, null, '1445843174', '0');
INSERT INTO {{%finance_header}} VALUES ('181', '3', '到位', '用户电话', '18', 'APP到位', '1', null, null, '1445843174', '0');
INSERT INTO {{%finance_header}} VALUES ('182', '4', '到位', '在线支付', '18', 'APP到位', '1', '', 'order_money', '1445843174', '0');
INSERT INTO {{%finance_header}} VALUES ('183', '5', '到位', '支付方式', '18', 'APP到位', '1', null, null, '1445843174', '0');
INSERT INTO {{%finance_header}} VALUES ('184', '6', '到位', '钱包支付', '18', 'APP到位', '1', null, null, '1445843174', '0');
INSERT INTO {{%finance_header}} VALUES ('185', '7', '到位', '代金券名称', '18', 'APP到位', '1', null, null, '1445843174', '0');
INSERT INTO {{%finance_header}} VALUES ('186', '8', '到位', '代金券金额', '18', 'APP到位', '1', '', 'order_channel_promote', '1445843174', '0');
INSERT INTO {{%finance_header}} VALUES ('187', '9', '到位', '流水号', '18', 'APP到位', '1', null, null, '1445843174', '0');
INSERT INTO {{%finance_header}} VALUES ('188', '10', '到位', '创建时间', '18', 'APP到位', '1', null, null, '1445843174', '0');
INSERT INTO {{%finance_header}} VALUES ('189', '11', '到位', '完成时间', '18', 'APP到位', '1', null, null, '1445843174', '0');
INSERT INTO {{%finance_header}} VALUES ('190', '0', '财付通支付', '序号', '0', '未知', '13', '财付通支付', null, '1447415793', '0');
INSERT INTO {{%finance_header}} VALUES ('191', '1', '财付通支付', '商家订单号', '0', '未知', '13', '财付通支付', null, '1447415793', '0');
INSERT INTO {{%finance_header}} VALUES ('192', '2', '财付通支付', '交易订单号', '0', '未知', '13', '财付通支付', 'order_channel_order_num', '1447415793', '0');
INSERT INTO {{%finance_header}} VALUES ('193', '3', '财付通支付', '交易时间', '0', '未知', '13', '财付通支付', null, '1447415793', '0');
INSERT INTO {{%finance_header}} VALUES ('194', '4', '财付通支付', '更新时间', '0', '未知', '13', '财付通支付', null, '1447415793', '0');
INSERT INTO {{%finance_header}} VALUES ('195', '5', '财付通支付', '支付类型', '0', '未知', '13', '财付通支付', null, '1447415793', '0');
INSERT INTO {{%finance_header}} VALUES ('196', '6', '财付通支付', '交易状态', '0', '未知', '13', '财付通支付', null, '1447415793', '0');
INSERT INTO {{%finance_header}} VALUES ('197', '7', '财付通支付', '分账状态', '0', '未知', '13', '财付通支付', null, '1447415793', '0');
INSERT INTO {{%finance_header}} VALUES ('198', '8', '财付通支付', '交易金额', '0', '未知', '13', '财付通支付', 'order_money', '1447415793', '0');"
    );
    }

    public function down()
    {
        $this->dropTable('{{%finance_header}}');

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
