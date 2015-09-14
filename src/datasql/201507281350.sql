/*
SQLyog Ultimate v11.24 (32 bit)
MySQL - 5.6.25 : Database - ejiajie_etrain
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

/*Table structure for table `et_answerlog` */

DROP TABLE IF EXISTS `et_answerlog`;

CREATE TABLE `et_answerlog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `answerer_id` int(11) NOT NULL DEFAULT '0' COMMENT '回答者ID',
  `answerer_name` varchar(100) DEFAULT NULL COMMENT '回答者名字',
  `question_id` int(11) NOT NULL DEFAULT '0' COMMENT '题目ID',
  `answer_options` varchar(255) DEFAULT NULL COMMENT '用户答案',
  `create_time` datetime NOT NULL COMMENT '回答时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `et_answerlog` */

/*Table structure for table `et_courseware` */

DROP TABLE IF EXISTS `et_courseware`;

CREATE TABLE `et_courseware` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL COMMENT '视频URL',
  `name` varchar(100) NOT NULL COMMENT '视频名称',
  `pv` int(11) DEFAULT '0' COMMENT '播放次数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `et_courseware` */

/*Table structure for table `et_question` */

DROP TABLE IF EXISTS `et_question`;

CREATE TABLE `et_question` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '题目',
  `courseware_id` int(11) DEFAULT NULL COMMENT '课件ID',
  `options` text NOT NULL COMMENT '答案选项集.JSON',
  `is_multi` tinyint(1) DEFAULT '0' COMMENT '是否多选。0单选，1多选',
  `correct_options` varchar(255) NOT NULL COMMENT '正确答案，逗号分隔。eg:A,B',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `et_question` */

/*Table structure for table `et_signed` */

DROP TABLE IF EXISTS `et_signed`;

CREATE TABLE `et_signed` (
  `sign_id` int(11) DEFAULT NULL COMMENT '自增编号',
  `uid` int(11) DEFAULT NULL COMMENT '用户id',
  `uname` varchar(50) DEFAULT NULL COMMENT '用户名称',
  `identity_number` varchar(18) DEFAULT NULL COMMENT '身份证号',
  `address` varchar(255) DEFAULT NULL COMMENT '联系地址',
  `emergency_contact` varchar(15) DEFAULT NULL COMMENT '紧急联系人',
  `shopid` varchar(20) DEFAULT NULL COMMENT '门店编号',
  `shopname` varchar(50) DEFAULT NULL COMMENT '门店名称',
  `bankcard` varchar(50) DEFAULT NULL COMMENT '银行卡号',
  `deposit` float DEFAULT NULL COMMENT '押金',
  `mobile` varchar(20) DEFAULT NULL COMMENT '手机号',
  `contract_number` varchar(50) DEFAULT NULL COMMENT '合同编号',
  `contract_time` int(11) DEFAULT NULL COMMENT '签订日期',
  `signed` varchar(20) DEFAULT NULL COMMENT '签约员工'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `et_signed` */

/*Table structure for table `et_studylog` */

DROP TABLE IF EXISTS `et_studylog`;

CREATE TABLE `et_studylog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `courseware_id` int(11) NOT NULL DEFAULT '0' COMMENT '课件ID',
  `start_time` datetime DEFAULT NULL COMMENT '开始时间',
  `end_time` datetime DEFAULT NULL COMMENT '结束时间',
  `status` tinyint(1) DEFAULT NULL COMMENT '状态 0:未开始 1:学习中 2:考试未通过 3:考试通过',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `et_studylog` */

/*Table structure for table `et_user` */

DROP TABLE IF EXISTS `et_user`;

CREATE TABLE `et_user` (
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
  `ecn` varchar(15) CHARACTER SET utf8 DEFAULT NULL COMMENT '紧急联系号码',
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户报名表';

/*Data for the table `et_user` */

/*Table structure for table `migration` */

DROP TABLE IF EXISTS `migration`;

CREATE TABLE `migration` (
  `version` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `migration` */

insert  into `migration`(`version`,`apply_time`) values ('m000000_000000_base',1435831872),('m130524_201442_init',1435831876);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
