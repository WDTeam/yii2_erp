<?php

use yii\db\Schema;
use jamband\schemadump\Migration;

class m151014_104903_create_table_ivrlog extends Migration
{
    public function safeUp()
    {
        $this->execute("
            CREATE TABLE IF NOT EXISTS {{%ivrlog}} (
              `id` int(11) NOT NULL AUTO_INCREMENT,
			  `ivrlog_req_tel` varchar(100) DEFAULT NULL COMMENT '电话号码',
			  `ivrlog_req_app_id` varchar(11) DEFAULT NULL COMMENT 'app_id',
			  `ivrlog_req_sign` varchar(255) DEFAULT NULL COMMENT '签名',
			  `ivrlog_req_timestamp` int(11) DEFAULT NULL COMMENT '请求时间',
			  `ivrlog_req_order_message` text COMMENT '消息',
			  `ivrlog_req_order_id` varchar(255) DEFAULT NULL COMMENT '订单号',
			  `ivrlog_res_result` tinyint(3) DEFAULT NULL COMMENT '结果',
			  `ivrlog_res_unique_id` varchar(255) DEFAULT NULL COMMENT '唯一标识',
			  `ivrlog_res_clid` varchar(255) DEFAULT NULL COMMENT '回显号码',
			  `ivrlog_res_description` varchar(255) DEFAULT NULL COMMENT '描述',
			  `ivrlog_cb_telephone` varchar(30) DEFAULT NULL COMMENT '回调电话号码',
			  `ivrlog_cb_order_id` varchar(255) DEFAULT NULL COMMENT '回调订单ID',
			  `ivrlog_cb_press` tinyint(1) DEFAULT NULL COMMENT '回调按键',
			  `ivrlog_cb_result` tinyint(3) DEFAULT NULL COMMENT '回调反馈码',
			  `ivrlog_cb_post_type` tinyint(1) DEFAULT NULL COMMENT '回调类型',
			  `ivrlog_cb_webcall_request_unique_id` varchar(150) DEFAULT NULL,
			  `ivrlog_cb_time` int(11) DEFAULT NULL COMMENT '回调时间',
			  PRIMARY KEY (`id`),
			  KEY `ivrlog_req_tel` (`ivrlog_req_tel`,`ivrlog_req_order_id`),
			  KEY `ivrlog_req_tel_2` (`ivrlog_req_tel`),
			  KEY `ivrlog_req_order_id` (`ivrlog_req_order_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8
        ");
    }

    public function safeDown()
    {
        $this->dropTable('{{%ivrlog}}');
    }
}
