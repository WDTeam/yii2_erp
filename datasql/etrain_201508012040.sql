# Host: 192.168.3.149  (Version: 5.5.5-10.0.16-MariaDB)
# Date: 2015-08-01 20:16:03
# Generator: MySQL-Front 5.3  (Build 4.120)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "et_adminuser"
#

DROP TABLE IF EXISTS `et_adminuser`;
CREATE TABLE `et_adminuser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(64) NOT NULL DEFAULT 'user',
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `role` (`role`),
  KEY `status` (`status`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='RBAC管理用户表';

#
# Data for table "et_adminuser"
#

INSERT INTO `et_adminuser` VALUES (1,'admin','1epI5YqrEp69yYopnIupWzaIbpbG45-M','$2y$13$H2h2XPss7i.FPQ3lCHamQu/qjqx8jEEFXwTR3vXdxpxQY.SpKBFSS','','admin@demo.com','admin',1,1438409505,1438409505);

#
# Structure for table "et_answerlog"
#

DROP TABLE IF EXISTS `et_answerlog`;
CREATE TABLE `et_answerlog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `answerer_id` int(11) NOT NULL DEFAULT '0' COMMENT '回答者ID',
  `answerer_name` varchar(100) DEFAULT NULL COMMENT '回答者名字',
  `question_id` int(11) NOT NULL DEFAULT '0' COMMENT '题目ID',
  `answer_options` varchar(255) DEFAULT NULL COMMENT '用户答案',
  `create_time` int(11) NOT NULL COMMENT '回答时间',
  `is_correct` tinyint(1) DEFAULT '0' COMMENT '是否正确？0不正确，1正确',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8;

#
# Data for table "et_answerlog"
#

INSERT INTO `et_answerlog` VALUES (59,1,'test',1,'C',1438167814,0),(60,1,'test',1,'B',1438167993,0),(61,1,'test',1,'A',1438222897,1),(62,1,'test',2,'B',1438222897,1),(63,1,'test',3,'C',1438222912,1),(64,1,'test',4,'A',1438222912,1),(65,1,'test',5,'D',1438222956,1),(66,1,'test',6,'A',1438222956,1),(67,1,'test',7,'A',1438222957,1),(68,1,'test',8,'A',1438222957,1),(69,1,'test',9,'A',1438222957,1),(70,1,'test',10,'B',1438222957,1),(71,1,'test',11,'A',1438222978,1),(72,1,'test',12,'A',1438222978,1),(73,1,'test',13,'A',1438222978,1),(74,1,'test',14,'C',1438222978,1),(75,1,'test',15,'B',1438222978,1),(76,1,'test',16,'D',1438222978,1),(77,1,'test',17,'A',1438222978,1),(78,1,'test',18,'C',1438222978,1),(79,1,'test',1,'A',1438308427,1),(80,1,'test',2,'B',1438308427,1),(81,92,'qqqq',1,'C',1438321720,0),(82,92,'qqqq',1,'A',1438321745,1),(83,92,'qqqq',2,'B',1438321745,1),(84,92,'qqqq',1,'A',1438327414,1),(85,92,'qqqq',2,'B',1438327415,1),(86,92,'qqqq',1,'A',1438395377,1),(87,92,'qqqq',2,'B',1438395377,1),(88,92,'qqqq',3,'C',1438395409,1),(89,92,'qqqq',4,'B',1438395409,0),(90,92,'qqqq',3,'C',1438395422,1),(91,92,'qqqq',4,'A',1438395422,1),(92,92,'qqqq',5,'D',1438395438,1),(93,92,'qqqq',6,'A',1438395438,1),(94,92,'qqqq',7,'A',1438395438,1),(95,92,'qqqq',8,'A',1438395438,1),(96,92,'qqqq',9,'A',1438395439,1),(97,92,'qqqq',10,'B',1438395439,1),(98,92,'qqqq',11,'A',1438395458,1),(99,92,'qqqq',12,'A',1438395458,1),(100,92,'qqqq',13,'A',1438395458,1),(101,92,'qqqq',14,'A',1438395458,0);

#
# Structure for table "et_auth_rule"
#

DROP TABLE IF EXISTS `et_auth_rule`;
CREATE TABLE `et_auth_rule` (
  `name` varchar(64) NOT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='RBAC规则表';

#
# Data for table "et_auth_rule"
#


#
# Structure for table "et_auth_item"
#

DROP TABLE IF EXISTS `et_auth_item`;
CREATE TABLE `et_auth_item` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `rule_name` varchar(64) DEFAULT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `et_auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `et_auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='RBAC权限角色表';

#
# Data for table "et_auth_item"
#

INSERT INTO `et_auth_item` VALUES ('admin',1,'Administrator',NULL,NULL,1438409506,1438409506),('author',1,NULL,NULL,NULL,1438409578,1438409578),('createPost',2,'create a post',NULL,NULL,1438409578,1438409578),('guest',1,'Guest',NULL,NULL,1438409506,1438409506),('reader',1,NULL,NULL,NULL,1438409578,1438409578),('readPost',2,'read a post',NULL,NULL,1438409578,1438409578),('updatePost',2,'update post',NULL,NULL,1438409578,1438409578),('user',1,'User',NULL,NULL,1438409506,1438409506);

#
# Structure for table "et_auth_item_child"
#

DROP TABLE IF EXISTS `et_auth_item_child`;
CREATE TABLE `et_auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `et_auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `et_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `et_auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `et_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='RBAC权限角色许可表';

#
# Data for table "et_auth_item_child"
#

INSERT INTO `et_auth_item_child` VALUES ('author','createPost'),('author','reader'),('reader','readPost');

#
# Structure for table "et_auth_assignment"
#

DROP TABLE IF EXISTS `et_auth_assignment`;
CREATE TABLE `et_auth_assignment` (
  `item_name` varchar(64) NOT NULL,
  `user_id` varchar(64) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  CONSTRAINT `et_auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `et_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='RBAC权限分配表';

#
# Data for table "et_auth_assignment"
#

INSERT INTO `et_auth_assignment` VALUES ('admin','1',1438409506),('author','1',1438409578),('reader','1',1438409578);

#
# Structure for table "et_courseware"
#

DROP TABLE IF EXISTS `et_courseware`;
CREATE TABLE `et_courseware` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL COMMENT '视频URL',
  `name` varchar(100) NOT NULL COMMENT '视频名称',
  `pv` int(11) DEFAULT '0' COMMENT '播放次数',
  `classify` enum('岗前学习','新技能学习') NOT NULL COMMENT '课件分类',
  `order_number` tinyint(4) DEFAULT '0' COMMENT '在分类下的排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

#
# Data for table "et_courseware"
#

INSERT INTO `et_courseware` VALUES (1,'http://5461.long-vod.cdn.aodianyun.com/u/5461/mp4/0x0/f4b667072e7a22eb69181e9d7e632889.mp4','公司介绍',0,'岗前学习',0),(2,'http://5461.long-vod.cdn.aodianyun.com/u/5461/mp4/0x0/642b7d90025817008afd76376ead494d.mp4','公司文化',0,'岗前学习',1),(3,'http://5461.long-vod.cdn.aodianyun.com/u/5461/mp4/0x0/c0b43976e9543eca76f35ea885314380.mp4','公司规章制度',0,'岗前学习',2),(4,'http://5461.long-vod.cdn.aodianyun.com/u/5461/mp4/0x0/038b11283bb0352aab4e490af775b723.mp4','服务规范',0,'岗前学习',3);

#
# Structure for table "et_question"
#

DROP TABLE IF EXISTS `et_question`;
CREATE TABLE `et_question` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '题目',
  `courseware_id` int(11) DEFAULT NULL COMMENT '课件ID',
  `options` text NOT NULL COMMENT '答案选项集.JSON',
  `is_multi` tinyint(1) DEFAULT '0' COMMENT '是否多选。0单选，1多选',
  `correct_options` varchar(255) NOT NULL COMMENT '正确答案，逗号分隔。eg:A,B',
  PRIMARY KEY (`id`),
  KEY `courseware_id` (`courseware_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

#
# Data for table "et_question"
#

INSERT INTO `et_question` VALUES (1,'公司名称叫什么？',1,'A、1家洁\r\nB、C家洁\r\nC、E家洁\r\nD、E家帮',0,'A'),(2,'以下描述哪种不正确',1,'A、E家洁是一家OTO家政服务公司\r\nB、E家洁只做小时工，不做企业保洁\r\nC、在E家洁平台接活，E家洁不抽成\r\nD、E家洁平台对家政服务人员有很多鼓励',0,'B'),(3,'公司企业文化是？',2,'A、家政是本，服务是根；以人为本，以客为尊。卓越服务，共创共赢。\r\nB、爱岗敬业，做好服务。\r\nC、用爱心为客户提供星级服务，用行动为家人创造幸福生活。\r\nD、全心全意服务每一位顾客。',0,'C'),(4,'公司服务意识是？',2,'A、家政是本，服务是根；以人为本，以客为尊。卓越服务，共创共赢。\r\nB、爱岗敬业，全心全意服务每一位顾客。\r\nC、用爱心为客户提供星级服务，用行动为家人创造幸福生活。\r\nD、快速响应、积极争取。',0,'A'),(5,'全月无投诉鼓励多少钱？',3,'A、50     \r\nB、80    \r\nC、70     \r\nD、100',0,'D'),(6,'如果顾客钱包放在餐桌上，此时你需要擦拭餐桌，你该怎么办？',3,'A、提醒顾客钱包在餐桌上，需要顾客收好\r\nB、不说话，埋头打扫\r\nC、把钱包翻看看一看\r\nD、把钱包偷偷放进自己的工具包内',0,'A'),(7,'关于拒单',3,'A、阿姨不得拒接直线距离7km以内订单\r\nB、阿姨不得拒接直线距离9km以内订单\r\nB、阿姨不得拒接直线距离10km以内订单\r\nD、阿姨不得拒直线距离12km以内订单',0,'A'),(8,'以下说法哪种正确',3,'A、迟到需提前致电顾客，获得谅解\r\nB、迟到无需提前致电顾客\r\nC、答应顾客的服务时间，因家里有事可以不去，也不用打电话告知顾客\r\nD、暂停接单无需提前在阿姨端进行操作',0,'A'),(9,'以下哪种物品禁止擦拭？',3,'A、神像、佛龛、古董、字画、吊灯\r\nB、桌子、椅子、床头柜\r\nC、油烟机、橱柜、垃圾桶\r\nD、窗户、门、家具表面',0,'A'),(10,'以下关于暂停接单哪种描述不正确？',3,'A、暂停接单需提前两天在阿姨端进行\r\nB、暂停接单只要告知门店管理人员，无需在阿姨端进行操作\r\nC、每年暂停接单天数不允许超过30天\r\nD、单次暂停接单天数不允许超过3天',0,'B'),(11,'清扫标准是？',4,'A、从上到下，从里到外，从左到右，从易到难\r\nB、从下到上，从里到外，从左到右，从易到难\r\nC、从后到前，从下到上，从里到外，从左到右\r\nD、从上到下，从外到里，从左到右，从易到难',0,'A'),(12,'公司规范的清扫流程是？',4,'A、卧室---书房—阳台—客厅—餐厅---厨房---卫生间\r\nB、书房---卧室—阳台—客厅—餐厅---厨房---卫生间\r\nC、卧室---书房—阳台—客厅—餐厅---卫生间---厨房\r\nD、卧室---书房—阳台—客厅—厨房---卫生间---餐厅',0,'A'),(13,'玻璃特性怕钝器和尖锐物，可使用工具有哪些？',4,'A、上水刷、刮水器、毛巾    \r\nB、钢丝球、刮水器、毛巾\r\nC、报纸、钢丝球、毛巾      \r\nD、上水刷、刮水器、钢丝球',0,'A'),(14,'以下哪种行为不符合公司规定？',4,'A、阿姨去客户家做保洁服务时需穿工服\r\nB、阿姨去客户家做保洁服务前手机需调成震动\r\nC、保洁服务完成后不需要帮顾客把垃圾带走\r\nD、阿姨不得拒接直线距离7km以内订单',0,'C'),(15,'以下哪种描述不正确？',4,'A、门把手、开关、地脚线、地垫也需要弄干净\r\nB、顾客家空的酒品、化妆瓶可以随便帮顾客扔掉\r\nC、清扫地面时一定要注意死角卫生\r\nD、清洁木地板需先用湿抹布擦一遍再用干抹布擦一遍，交替擦拭',0,'B'),(16,'以下哪种描述不正确？',4,'A、更换垃圾袋时，也需要把垃圾桶内外清洗干净\r\nB、油烟机、灶台也属于服务范围内\r\nC、清理马桶时里外及死角都要刷干净\r\nD、擦桌椅时只需擦表面即可，无需擦桌腿、椅腿',0,'D'),(17,'以下哪种描述不正确？',4,'A、服务结束后不需要让顾客进行检查\r\nB、与顾客沟通时需面带笑容\r\nC、清理卫生间前一定要先检查池子、马桶下水是否通畅\r\nD、挪动桌椅时禁止拖、拉，需要搬起',0,'A'),(18,'公司禁止使用的工具是',4,'A、百洁布     \r\nB、清洁刷    \r\nC、钢丝球    \r\nD、抹布',0,'C');

#
# Structure for table "et_signed"
#

DROP TABLE IF EXISTS `et_signed`;
CREATE TABLE `et_signed` (
  `uid` int(11) NOT NULL COMMENT '用户id',
  `uname` varchar(50) DEFAULT NULL COMMENT '用户名称',
  `identity_number` varchar(18) DEFAULT NULL COMMENT '身份证号',
  `address` varchar(255) DEFAULT NULL COMMENT '联系地址',
  `emergency_persion` varchar(50) DEFAULT NULL COMMENT '紧急联系人',
  `emergency_contact` varchar(15) DEFAULT NULL COMMENT '紧急联系电话',
  `shopid` varchar(20) DEFAULT NULL COMMENT '门店编号',
  `shopname` varchar(50) DEFAULT NULL COMMENT '门店名称',
  `bankcard` varchar(50) DEFAULT NULL COMMENT '银行卡号',
  `deposit` float DEFAULT NULL COMMENT '押金',
  `mobile` varchar(20) DEFAULT NULL COMMENT '手机号',
  `contract_number` varchar(50) DEFAULT NULL COMMENT '合同编号',
  `contract_time` int(11) DEFAULT NULL COMMENT '签订日期',
  `signed` varchar(20) DEFAULT NULL COMMENT '签约员工',
  `sendSome` varchar(200) DEFAULT NULL COMMENT '发放物品',
  `picture` varchar(255) DEFAULT NULL COMMENT '照片',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Data for table "et_signed"
#


#
# Structure for table "et_studylog"
#

DROP TABLE IF EXISTS `et_studylog`;
CREATE TABLE `et_studylog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `courseware_id` int(11) NOT NULL DEFAULT '0' COMMENT '课件ID',
  `start_time` int(11) DEFAULT NULL COMMENT '开始时间',
  `end_time` int(11) DEFAULT NULL COMMENT '结束时间',
  `status` tinyint(1) DEFAULT NULL COMMENT '状态 0:未开始 1:学习中 2:考试未通过 3:考试通过',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

#
# Data for table "et_studylog"
#

INSERT INTO `et_studylog` VALUES (9,1,1,2147483647,2147483647,3),(10,1,2,2147483647,2147483647,3),(11,1,3,2147483647,2147483647,3),(12,1,4,2015,2015,3),(13,92,1,1438315423,1438315436,3),(14,92,2,1438321745,1438321751,3),(15,92,3,1438395422,1438395424,3),(16,92,4,1438395439,1438395443,2);

#
# Structure for table "et_user"
#

DROP TABLE IF EXISTS `et_user`;
CREATE TABLE `et_user` (
  `common_mobile` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '常用的手机号',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '姓名',
  `auth_key` char(32) CHARACTER SET utf8 DEFAULT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '加密的密码',
  `password_reset_token` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '密码重置',
  `email` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '邮箱',
  `idnumber` varchar(24) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '身份证号',
  `age` smallint(3) DEFAULT NULL COMMENT '年龄',
  `birthday` date DEFAULT NULL COMMENT '生日',
  `mobile` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '手机',
  `ecp` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '紧急联系人',
  `ecn` varchar(15) CHARACTER SET utf8 DEFAULT NULL COMMENT '紧急联系号码',
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '住址',
  `city` char(3) CHARACTER SET utf8 DEFAULT NULL COMMENT '市',
  `province` char(3) CHARACTER SET utf8 DEFAULT NULL COMMENT '省',
  `district` char(2) CHARACTER SET utf8 DEFAULT NULL COMMENT '区',
  `whatodo` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '提供的服务列表',
  `where` char(1) CHARACTER SET utf8 DEFAULT NULL COMMENT '来自哪里',
  `when` char(1) CHARACTER SET utf8 DEFAULT NULL COMMENT '选择服务时间',
  `status` smallint(6) DEFAULT NULL COMMENT '状态',
  `created_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `updated_at` int(11) DEFAULT NULL COMMENT '更新时间',
  `isdel` char(1) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '1',
  `study_status` smallint(1) DEFAULT NULL COMMENT '学习状态:1认同，2不认同',
  `study_time` int(11) DEFAULT NULL COMMENT '学习所用时长（单位秒）',
  `notice_status` smallint(1) DEFAULT NULL COMMENT '通知状态：1已通知，2未通知',
  `online_exam_time` int(11) DEFAULT NULL COMMENT '在线考试时间（开始还是结束呢？）',
  `online_exam_score` int(11) DEFAULT NULL COMMENT '在线考试成绩',
  `exam_result` smallint(1) DEFAULT NULL COMMENT '现场考试结果：1通过，2未通过',
  `operation_time` int(11) DEFAULT NULL COMMENT '实操考试时间',
  `operation_score` int(11) DEFAULT NULL COMMENT '实操考试成绩',
  `test_status` smallint(1) DEFAULT NULL COMMENT '试工状态：1.安排试工，2：不用试工',
  `test_situation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '试工情况',
  `test_result` int(11) DEFAULT NULL COMMENT '试工结果',
  `sign_status` smallint(1) DEFAULT NULL COMMENT '签约状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户报名表';

#
# Data for table "et_user"
#

INSERT INTO `et_user` VALUES (NULL,92,'qqqq',NULL,'123123',NULL,NULL,'1111',NULL,'0000-00-00','11111',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1222222222,NULL,NULL,NULL,NULL,2,90,90,1,NULL,1,1,NULL,1,2),(NULL,93,'刘海舰',NULL,NULL,NULL,NULL,'130681198709072323',NULL,NULL,'18614079208',NULL,'186120',NULL,'朝阳区','北京市',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);

#
# Structure for table "migration"
#

DROP TABLE IF EXISTS `migration`;
CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

#
# Data for table "migration"
#

/*!40000 ALTER TABLE `migration` DISABLE KEYS */;
INSERT INTO `migration` VALUES ('m000000_000000_base',1438409496),('m140608_201405_user_init',1438409506),('m140608_201406_rbac_init',1438409506),('m140708_201431_rbac_init',1438409578);
/*!40000 ALTER TABLE `migration` ENABLE KEYS */;
