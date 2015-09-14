/*
SQLyog 企业版 - MySQL GUI v8.14 
MySQL - 5.6.24 : Database - etrain
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`etrain` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `etrain`;

/*Table structure for table `signed` */

DROP TABLE IF EXISTS `signed`;

CREATE TABLE `signed` (
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

/*Data for the table `signed` */

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '姓名',
  `auth_key` char(4) CHARACTER SET utf8 DEFAULT NULL COMMENT '手机验证码x',
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

/*Data for the table `user` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
