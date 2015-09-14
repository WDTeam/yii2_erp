/*
SQLyog Ultimate v11.24 (32 bit)
MySQL - 5.6.16-log : Database - train
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`train` /*!40100 DEFAULT CHARACTER SET utf8 */;

/*Table structure for table `et_answerlog` */

DROP TABLE IF EXISTS `et_answerlog`;

CREATE TABLE `et_answerlog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `answerer_id` int(11) NOT NULL DEFAULT '0' COMMENT '回答者ID',
  `answerer_name` varchar(100) DEFAULT NULL COMMENT '回答者名字',
  `question_id` int(11) NOT NULL DEFAULT '0' COMMENT '题目ID',
  `answer_options` varchar(255) DEFAULT NULL COMMENT '用户答案',
  `create_time` int(11) NOT NULL COMMENT '回答时间',
  `is_correct` tinyint(1) DEFAULT '0' COMMENT '是否正确？0不正确，1正确',
  `classify_id` int(11) DEFAULT NULL COMMENT '问题所属分类id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

/*Data for the table `et_answerlog` */

insert  into `et_answerlog`(`id`,`answerer_id`,`answerer_name`,`question_id`,`answer_options`,`create_time`,`is_correct`,`classify_id`) values (1,291,'大连',21,'A',1439979295,1,5),(2,0,NULL,0,'C',1439999502,0,NULL),(3,0,NULL,0,'B',1439999509,1,NULL),(4,0,NULL,0,'A',1439999536,0,NULL),(5,0,NULL,0,'A',1439999543,0,NULL),(6,0,NULL,0,'B',1439999549,1,NULL),(7,0,NULL,0,'C',1440035147,0,NULL),(8,0,NULL,0,'A',1440035157,0,NULL),(9,0,NULL,0,'A',1440035222,0,NULL),(10,0,NULL,0,'B',1440035234,1,NULL),(11,0,NULL,0,'B',1440136672,1,NULL),(12,0,NULL,0,'A',1440136773,0,NULL),(13,0,NULL,0,'�',1440138194,0,NULL),(14,0,NULL,0,'�',1440140769,0,NULL),(15,114,'士大夫',1,'C',1440144863,1,NULL),(16,114,'士大夫',2,'B',1440144863,1,NULL);

/*Table structure for table `et_auth_assignment` */

DROP TABLE IF EXISTS `et_auth_assignment`;

CREATE TABLE `et_auth_assignment` (
  `item_name` varchar(64) NOT NULL,
  `user_id` varchar(64) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  CONSTRAINT `et_auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `et_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='RBAC权限分配表';

/*Data for the table `et_auth_assignment` */

insert  into `et_auth_assignment`(`item_name`,`user_id`,`created_at`) values ('admin','1',1438409506),('author','1',1438409578),('reader','1',1438409578);

/*Table structure for table `et_auth_item` */

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

/*Data for the table `et_auth_item` */

insert  into `et_auth_item`(`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`) values ('admin',1,'Administrator',NULL,NULL,1438409506,1438409506),('author',1,NULL,NULL,NULL,1438409578,1438409578),('createPost',2,'create a post',NULL,NULL,1438409578,1438409578),('guest',1,'Guest',NULL,NULL,1438409506,1438409506),('reader',1,NULL,NULL,NULL,1438409578,1438409578),('readPost',2,'read a post',NULL,NULL,1438409578,1438409578),('updatePost',2,'update post',NULL,NULL,1438409578,1438409578),('user',1,'User',NULL,NULL,1438409506,1438409506);

/*Table structure for table `et_auth_item_child` */

DROP TABLE IF EXISTS `et_auth_item_child`;

CREATE TABLE `et_auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `et_auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `et_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `et_auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `et_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='RBAC权限角色许可表';

/*Data for the table `et_auth_item_child` */

insert  into `et_auth_item_child`(`parent`,`child`) values ('author','createPost'),('author','reader'),('reader','readPost');

/*Table structure for table `et_auth_rule` */

DROP TABLE IF EXISTS `et_auth_rule`;

CREATE TABLE `et_auth_rule` (
  `name` varchar(64) NOT NULL,
  `data` text,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='RBAC规则表';

/*Data for the table `et_auth_rule` */

/*Table structure for table `et_cache` */

DROP TABLE IF EXISTS `et_cache`;

CREATE TABLE `et_cache` (
  `id` char(128) NOT NULL,
  `expire` int(11) DEFAULT NULL,
  `data` blob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `et_cache` */

insert  into `et_cache`(`id`,`expire`,`data`) values ('714bb029014e0971bf80f6acdf9c833b',1440211210,'a:2:{i:0;i:2134;i:1;N;}'),('963454f612a8b5fb4a63ba1e97f028a1',0,'a:2:{i:0;a:2:{i:0;a:4:{i:0;O:15:\"yii\\web\\UrlRule\":13:{s:4:\"name\";s:25:\"<controller:\\w+>/<id:\\d+>\";s:7:\"pattern\";s:36:\"#^(?P<controller>\\w+)/(?P<id>\\d+)$#u\";s:4:\"host\";N;s:5:\"route\";s:17:\"<controller>/view\";s:8:\"defaults\";a:0:{}s:6:\"suffix\";N;s:4:\"verb\";N;s:4:\"mode\";N;s:12:\"encodeParams\";b:1;s:26:\"\0yii\\web\\UrlRule\0_template\";s:19:\"/<controller>/<id>/\";s:27:\"\0yii\\web\\UrlRule\0_routeRule\";s:29:\"#^(?P<controller>\\w+)/view$#u\";s:28:\"\0yii\\web\\UrlRule\0_paramRules\";a:1:{s:2:\"id\";s:8:\"#^\\d+$#u\";}s:29:\"\0yii\\web\\UrlRule\0_routeParams\";a:1:{s:10:\"controller\";s:12:\"<controller>\";}}i:1;O:15:\"yii\\web\\UrlRule\":13:{s:4:\"name\";s:38:\"<controller:\\w+>/<action:\\w+>/<id:\\d+>\";s:7:\"pattern\";s:52:\"#^(?P<controller>\\w+)/(?P<action>\\w+)/(?P<id>\\d+)$#u\";s:4:\"host\";N;s:5:\"route\";s:21:\"<controller>/<action>\";s:8:\"defaults\";a:0:{}s:6:\"suffix\";N;s:4:\"verb\";N;s:4:\"mode\";N;s:12:\"encodeParams\";b:1;s:26:\"\0yii\\web\\UrlRule\0_template\";s:28:\"/<controller>/<action>/<id>/\";s:27:\"\0yii\\web\\UrlRule\0_routeRule\";s:40:\"#^(?P<controller>\\w+)/(?P<action>\\w+)$#u\";s:28:\"\0yii\\web\\UrlRule\0_paramRules\";a:1:{s:2:\"id\";s:8:\"#^\\d+$#u\";}s:29:\"\0yii\\web\\UrlRule\0_routeParams\";a:2:{s:10:\"controller\";s:12:\"<controller>\";s:6:\"action\";s:8:\"<action>\";}}i:2;O:15:\"yii\\web\\UrlRule\":13:{s:4:\"name\";s:29:\"<controller:\\w+>/<action:\\w+>\";s:7:\"pattern\";s:40:\"#^(?P<controller>\\w+)/(?P<action>\\w+)$#u\";s:4:\"host\";N;s:5:\"route\";s:21:\"<controller>/<action>\";s:8:\"defaults\";a:0:{}s:6:\"suffix\";N;s:4:\"verb\";N;s:4:\"mode\";N;s:12:\"encodeParams\";b:1;s:26:\"\0yii\\web\\UrlRule\0_template\";s:23:\"/<controller>/<action>/\";s:27:\"\0yii\\web\\UrlRule\0_routeRule\";s:40:\"#^(?P<controller>\\w+)/(?P<action>\\w+)$#u\";s:28:\"\0yii\\web\\UrlRule\0_paramRules\";a:0:{}s:29:\"\0yii\\web\\UrlRule\0_routeParams\";a:2:{s:10:\"controller\";s:12:\"<controller>\";s:6:\"action\";s:8:\"<action>\";}}i:3;O:15:\"yii\\web\\UrlRule\":13:{s:4:\"name\";s:38:\"<controller:\\w+>/<action:\\w+>/<id:\\w+>\";s:7:\"pattern\";s:52:\"#^(?P<controller>\\w+)/(?P<action>\\w+)/(?P<id>\\w+)$#u\";s:4:\"host\";N;s:5:\"route\";s:21:\"<controller>/<action>\";s:8:\"defaults\";a:0:{}s:6:\"suffix\";N;s:4:\"verb\";N;s:4:\"mode\";N;s:12:\"encodeParams\";b:1;s:26:\"\0yii\\web\\UrlRule\0_template\";s:28:\"/<controller>/<action>/<id>/\";s:27:\"\0yii\\web\\UrlRule\0_routeRule\";s:40:\"#^(?P<controller>\\w+)/(?P<action>\\w+)$#u\";s:28:\"\0yii\\web\\UrlRule\0_paramRules\";a:1:{s:2:\"id\";s:8:\"#^\\w+$#u\";}s:29:\"\0yii\\web\\UrlRule\0_routeParams\";a:2:{s:10:\"controller\";s:12:\"<controller>\";s:6:\"action\";s:8:\"<action>\";}}}i:1;s:32:\"5481a150606b3ba4001792ab8437a62e\";}i:1;N;}'),('a6ff272329dd0c1056f4d74fa8f7f866',1440228764,'a:2:{i:0;i:3270;i:1;N;}'),('dfd1e95e9b5bdf7fb9e3ca6d51952cc9',1440223008,'a:2:{i:0;i:8496;i:1;N;}');

/*Table structure for table `et_category` */

DROP TABLE IF EXISTS `et_category`;

CREATE TABLE `et_category` (
  `cateid` int(11) NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `catename` varchar(30) DEFAULT NULL COMMENT '分类名称',
  `description` text COMMENT '分类描述',
  PRIMARY KEY (`cateid`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

/*Data for the table `et_category` */

insert  into `et_category`(`cateid`,`catename`,`description`) values (1,'岗前学习',''),(5,'北京你好','北京欢迎你'),(7,'空调维修','空调维修空调维修空调维修'),(11,'测试2',''),(12,'测试3',''),(13,'测试4',''),(14,'测试5',''),(15,'测试11',''),(16,'测试111','');

/*Table structure for table `et_courseware` */

DROP TABLE IF EXISTS `et_courseware`;

CREATE TABLE `et_courseware` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `image` varchar(255) DEFAULT NULL COMMENT '图片URL',
  `url` varchar(255) NOT NULL COMMENT '视频URL',
  `name` varchar(100) NOT NULL COMMENT '视频名称',
  `pv` int(11) DEFAULT '0' COMMENT '播放次数',
  `order_number` int(4) DEFAULT '9999' COMMENT '在分类下的排序',
  `classify_id` int(11) NOT NULL DEFAULT '0' COMMENT '服务技能ID',
  `classify` enum('岗前学习') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

/*Data for the table `et_courseware` */

insert  into `et_courseware`(`id`,`image`,`url`,`name`,`pv`,`order_number`,`classify_id`,`classify`) values (1,'http://cdn.dvr.aodianyun.com/pic/long-vod/u/5461/images/f4b667072e7a22eb69181e9d7e632889/0/0','http://5461.long-vod.cdn.aodianyun.com/u/5461/m3u8/adaptive/f4b667072e7a22eb69181e9d7e632889.m3u8','公司介绍',0,0,1,'岗前学习'),(2,'http://cdn.dvr.aodianyun.com/pic/long-vod/u/5461/images/642b7d90025817008afd76376ead494d/0/0','http://5461.long-vod.cdn.aodianyun.com/u/5461/m3u8/adaptive/642b7d90025817008afd76376ead494d.m3u8','公司文化',0,2,1,'岗前学习'),(3,'http://cdn.dvr.aodianyun.com/pic/long-vod/u/5461/images/c0b43976e9543eca76f35ea885314380/0/0','http://5461.long-vod.cdn.aodianyun.com/u/5461/m3u8/adaptive/c0b43976e9543eca76f35ea885314380.m3u8','公司规章制度',0,3,1,'岗前学习'),(4,'http://cdn.dvr.aodianyun.com/pic/long-vod/u/5461/images/038b11283bb0352aab4e490af775b723/0/0','http://5461.long-vod.cdn.aodianyun.com/u/5461/m3u8/adaptive/038b11283bb0352aab4e490af775b723.m3u8','服务规范',0,4,1,'岗前学习'),(5,'http://cdn.dvr.aodianyun.com/pic/long-vod/u/5461/images/8a9659f21c560d116aa3edf141fb209d/0/0','http://5461.long-vod.cdn.aodianyun.com/u/5461/m3u8/854x480/8a9659f21c560d116aa3edf141fb209d/8a9659f21c560d116aa3edf141fb209d.m3u8','片头',0,5,2,NULL),(7,'http://cdn.dvr.aodianyun.com/pic/long-vod/u/5461/images/f4b667072e7a22eb69181e9d7e632889/0/0','http://5461.long-vod.cdn.aodianyun.com/u/5461/m3u8/854x480/f4b667072e7a22eb69181e9d7e632889/f4b667072e7a22eb69181e9d7e632889.m3u8','公司简介',0,9,3,NULL),(8,'http://cdn.dvr.aodianyun.com/pic/long-vod/u/5461/images/642b7d90025817008afd76376ead494d/0/0','http://5461.long-vod.cdn.aodianyun.com/u/5461/m3u8/854x480/642b7d90025817008afd76376ead494d/642b7d90025817008afd76376ead494d.m3u8','企业文化',0,10,3,NULL),(9,'http://cdn.dvr.aodianyun.com/pic/long-vod/u/5461/images/642b7d90025817008afd76376ead494d/0/0','http://5461.long-vod.cdn.aodianyun.com/u/5461/m3u8/854x480/642b7d90025817008afd76376ead494d/642b7d90025817008afd76376ead494d.m3u8','企业文化',0,6,2,NULL),(10,'http://cdn.dvr.aodianyun.com/pic/long-vod/u/5461/images/f4b667072e7a22eb69181e9d7e632889/0/0','http://5461.long-vod.cdn.aodianyun.com/u/5461/m3u8/854x480/f4b667072e7a22eb69181e9d7e632889/f4b667072e7a22eb69181e9d7e632889.m3u8','公司简介',0,11,4,NULL),(11,'http://cdn.dvr.aodianyun.com/pic/long-vod/u/5461/images/f4b667072e7a22eb69181e9d7e632889/0/0','http://5461.long-vod.cdn.aodianyun.com/u/5461/m3u8/854x480/f4b667072e7a22eb69181e9d7e632889/f4b667072e7a22eb69181e9d7e632889.m3u8','公司简介',0,7,2,NULL),(12,'http://cdn.dvr.aodianyun.com/pic/long-vod/u/5461/images/c0b43976e9543eca76f35ea885314380/0/0','http://5461.long-vod.cdn.aodianyun.com/u/5461/m3u8/854x480/c0b43976e9543eca76f35ea885314380/c0b43976e9543eca76f35ea885314380.m3u8','e家洁规章制度',0,8,2,NULL),(13,'http://cdn.dvr.aodianyun.com/pic/long-vod/u/5461/images/f4b667072e7a22eb69181e9d7e632889/0/0','http://5461.long-vod.cdn.aodianyun.com/u/5461/m3u8/854x480/f4b667072e7a22eb69181e9d7e632889/f4b667072e7a22eb69181e9d7e632889.m3u8','公司简介',0,17,5,NULL),(15,'http://cdn.dvr.aodianyun.com/pic/long-vod/u/5461/images/038b11283bb0352aab4e490af775b723/0/0','http://5461.long-vod.cdn.aodianyun.com/u/5461/m3u8/854x480/038b11283bb0352aab4e490af775b723/038b11283bb0352aab4e490af775b723.m3u8','工作服务规范',0,13,5,NULL),(16,'http://cdn.dvr.aodianyun.com/pic/long-vod/u/5461/images/8a9659f21c560d116aa3edf141fb209d/0/0','http://5461.long-vod.cdn.aodianyun.com/u/5461/m3u8/854x480/8a9659f21c560d116aa3edf141fb209d/8a9659f21c560d116aa3edf141fb209d.m3u8','片头',0,14,6,NULL),(17,'http://cdn.dvr.aodianyun.com/pic/long-vod/u/5461/images/8a9659f21c560d116aa3edf141fb209d/0/0','http://5461.long-vod.cdn.aodianyun.com/u/5461/m3u8/854x480/8a9659f21c560d116aa3edf141fb209d/8a9659f21c560d116aa3edf141fb209d.m3u8','片头',0,15,7,NULL),(18,'http://cdn.dvr.aodianyun.com/pic/long-vod/u/5461/images/642b7d90025817008afd76376ead494d/0/0','http://5461.long-vod.cdn.aodianyun.com/u/5461/m3u8/854x480/642b7d90025817008afd76376ead494d/642b7d90025817008afd76376ead494d.m3u8','企业文化',0,16,7,NULL),(19,'http://cdn.dvr.aodianyun.com/pic/long-vod/u/5461/images/642b7d90025817008afd76376ead494d/0/0','http://5461.long-vod.cdn.aodianyun.com/u/5461/m3u8/854x480/642b7d90025817008afd76376ead494d/642b7d90025817008afd76376ead494d.m3u8','企业文化',0,18,5,NULL),(20,'http://cdn.dvr.aodianyun.com/pic/long-vod/u/5461/images/642b7d90025817008afd76376ead494d/0/0','http://5461.long-vod.cdn.aodianyun.com/u/5461/m3u8/854x480/642b7d90025817008afd76376ead494d/642b7d90025817008afd76376ead494d.m3u8','企业文化',0,12,5,NULL),(21,'http://cdn.dvr.aodianyun.com/pic/long-vod/u/5461/images/8a9659f21c560d116aa3edf141fb209d/0/0','http://5461.long-vod.cdn.aodianyun.com/u/5461/m3u8/854x480/8a9659f21c560d116aa3edf141fb209d/8a9659f21c560d116aa3edf141fb209d.m3u8','片头',0,19,5,NULL),(22,'http://cdn.dvr.aodianyun.com/pic/long-vod/u/5461/images/642b7d90025817008afd76376ead494d/0/0','http://5461.long-vod.cdn.aodianyun.com/u/5461/m3u8/854x480/642b7d90025817008afd76376ead494d/642b7d90025817008afd76376ead494d.m3u8','企业文化',0,20,5,NULL),(23,'http://cdn.dvr.aodianyun.com/pic/long-vod/u/5461/images/f4b667072e7a22eb69181e9d7e632889/0/0','http://5461.long-vod.cdn.aodianyun.com/u/5461/m3u8/854x480/f4b667072e7a22eb69181e9d7e632889/f4b667072e7a22eb69181e9d7e632889.m3u8','公司简介',0,21,5,NULL),(24,'http://cdn.dvr.aodianyun.com/pic/long-vod/u/5461/images/038b11283bb0352aab4e490af775b723/0/0','http://5461.long-vod.cdn.aodianyun.com/u/5461/m3u8/854x480/038b11283bb0352aab4e490af775b723/038b11283bb0352aab4e490af775b723.m3u8','工作服务规范',0,22,5,NULL),(25,'http://cdn.dvr.aodianyun.com/pic/long-vod/u/5461/images/c0b43976e9543eca76f35ea885314380/0/0','http://5461.long-vod.cdn.aodianyun.com/u/5461/m3u8/854x480/c0b43976e9543eca76f35ea885314380/c0b43976e9543eca76f35ea885314380.m3u8','e家洁规章制度',0,23,5,NULL),(26,'http://cdn.dvr.aodianyun.com/pic/long-vod/u/5461/images/f4b667072e7a22eb69181e9d7e632889/0/0','http://5461.long-vod.cdn.aodianyun.com/u/5461/m3u8/854x480/f4b667072e7a22eb69181e9d7e632889/f4b667072e7a22eb69181e9d7e632889.m3u8','公司简介',0,25,5,NULL),(27,'http://cdn.dvr.aodianyun.com/pic/long-vod/u/5461/images/642b7d90025817008afd76376ead494d/0/0','http://5461.long-vod.cdn.aodianyun.com/u/5461/m3u8/854x480/642b7d90025817008afd76376ead494d/642b7d90025817008afd76376ead494d.m3u8','企业文化',0,24,5,NULL),(28,'http://cdn.dvr.aodianyun.com/pic/long-vod/u/5461/images/642b7d90025817008afd76376ead494d/0/0','http://5461.long-vod.cdn.aodianyun.com/u/5461/m3u8/854x480/642b7d90025817008afd76376ead494d/642b7d90025817008afd76376ead494d.m3u8','企业文化',0,26,10,NULL),(29,'http://cdn.dvr.aodianyun.com/pic/long-vod/u/5461/images/642b7d90025817008afd76376ead494d/0/0','http://5461.long-vod.cdn.aodianyun.com/u/5461/m3u8/854x480/642b7d90025817008afd76376ead494d/642b7d90025817008afd76376ead494d.m3u8','企业文化',0,27,11,NULL),(30,'http://cdn.dvr.aodianyun.com/pic/long-vod/u/5461/images/8a9659f21c560d116aa3edf141fb209d/0/0','http://5461.long-vod.cdn.aodianyun.com/u/5461/m3u8/854x480/8a9659f21c560d116aa3edf141fb209d/8a9659f21c560d116aa3edf141fb209d.m3u8','片头',0,28,11,NULL);

/*Table structure for table `et_question` */

DROP TABLE IF EXISTS `et_question`;

CREATE TABLE `et_question` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '题目',
  `courseware_id` int(11) DEFAULT NULL COMMENT '课件ID',
  `options` text NOT NULL COMMENT '答案选项集.JSON',
  `is_multi` tinyint(1) DEFAULT '0' COMMENT '是否多选。0单选，1多选',
  `correct_options` varchar(255) NOT NULL COMMENT '正确答案，逗号分隔。eg:A,B',
  `category_id` int(11) DEFAULT '0' COMMENT '服务ID',
  PRIMARY KEY (`id`),
  KEY `courseware_id` (`courseware_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

/*Data for the table `et_question` */

insert  into `et_question`(`id`,`title`,`courseware_id`,`options`,`is_multi`,`correct_options`,`category_id`) values (1,'公司名称叫什么？',1,'A、1家洁\r\nB、C家洁\r\nC、E家洁\r\nD、E家帮',0,'C',0),(2,'以下描述哪种不正确',1,'A、E家洁是一家OTO家政服务公司\r\nB、E家洁只做小时工，不做企业保洁\r\nC、在E家洁平台接活，E家洁不抽成\r\nD、E家洁平台对家政服务人员有很多鼓励',0,'B',0),(3,'公司企业文化是？',2,'A、家政是本，服务是根；以人为本，以客为尊。卓越服务，共创共赢。\r\nB、爱岗敬业，做好服务。\r\nC、用爱心为客户提供星级服务，用行动为家人创造幸福生活。\r\nD、全心全意服务每一位顾客。',0,'C',0),(4,'公司服务意识是？',2,'A、家政是本，服务是根；以人为本，以客为尊。卓越服务，共创共赢。\r\nB、爱岗敬业，全心全意服务每一位顾客。\r\nC、用爱心为客户提供星级服务，用行动为家人创造幸福生活。\r\nD、快速响应、积极争取。',0,'A',0),(5,'全月无投诉鼓励多少钱？',3,'A、50     \r\nB、80    \r\nC、70     \r\nD、100',0,'D',0),(6,'如果顾客钱包放在餐桌上，此时你需要擦拭餐桌，你该怎么办？',3,'A、提醒顾客钱包在餐桌上，需要顾客收好\r\nB、不说话，埋头打扫\r\nC、把钱包翻看看一看\r\nD、把钱包偷偷放进自己的工具包内',0,'A',0),(7,'关于拒单',3,'A、阿姨不得拒接直线距离7km以内订单\r\nB、阿姨不得拒接直线距离9km以内订单\r\nB、阿姨不得拒接直线距离10km以内订单\r\nD、阿姨不得拒直线距离12km以内订单',0,'A',0),(8,'以下说法哪种正确',3,'A、迟到需提前致电顾客，获得谅解\r\nB、迟到无需提前致电顾客\r\nC、答应顾客的服务时间，因家里有事可以不去，也不用打电话告知顾客\r\nD、暂停接单无需提前在阿姨端进行操作',0,'A',0),(9,'以下哪种物品禁止擦拭？',3,'A、神像、佛龛、古董、字画、吊灯\r\nB、桌子、椅子、床头柜\r\nC、油烟机、橱柜、垃圾桶\r\nD、窗户、门、家具表面',0,'A',0),(10,'以下关于暂停接单哪种描述不正确？',3,'A、暂停接单需提前两天在阿姨端进行\r\nB、暂停接单只要告知门店管理人员，无需在阿姨端进行操作\r\nC、每年暂停接单天数不允许超过30天\r\nD、单次暂停接单天数不允许超过3天',0,'B',0),(11,'清扫标准是？',4,'A、从上到下，从里到外，从左到右，从易到难\r\nB、从下到上，从里到外，从左到右，从易到难\r\nC、从后到前，从下到上，从里到外，从左到右\r\nD、从上到下，从外到里，从左到右，从易到难',0,'A',0),(12,'公司规范的清扫流程是？',4,'A、卧室---书房—阳台—客厅—餐厅---厨房---卫生间\r\nB、书房---卧室—阳台—客厅—餐厅---厨房---卫生间\r\nC、卧室---书房—阳台—客厅—餐厅---卫生间---厨房\r\nD、卧室---书房—阳台—客厅—厨房---卫生间---餐厅',0,'A',0),(13,'玻璃特性怕钝器和尖锐物，可使用工具有哪些？',4,'A、上水刷、刮水器、毛巾    \r\nB、钢丝球、刮水器、毛巾\r\nC、报纸、钢丝球、毛巾      \r\nD、上水刷、刮水器、钢丝球',0,'A',0),(14,'以下哪种行为不符合公司规定？',4,'A、阿姨去客户家做保洁服务时需穿工服\r\nB、阿姨去客户家做保洁服务前手机需调成震动\r\nC、保洁服务完成后不需要帮顾客把垃圾带走\r\nD、阿姨不得拒接直线距离7km以内订单',0,'C',0),(15,'以下哪种描述不正确？',4,'A、门把手、开关、地脚线、地垫也需要弄干净\r\nB、顾客家空的酒品、化妆瓶可以随便帮顾客扔掉\r\nC、清扫地面时一定要注意死角卫生\r\nD、清洁木地板需先用湿抹布擦一遍再用干抹布擦一遍，交替擦拭',0,'B',0),(16,'以下哪种描述不正确？',4,'A、更换垃圾袋时，也需要把垃圾桶内外清洗干净\r\nB、油烟机、灶台也属于服务范围内\r\nC、清理马桶时里外及死角都要刷干净\r\nD、擦桌椅时只需擦表面即可，无需擦桌腿、椅腿',0,'D',0),(17,'以下哪种描述不正确？',4,'A、服务结束后不需要让顾客进行检查\r\nB、与顾客沟通时需面带笑容\r\nC、清理卫生间前一定要先检查池子、马桶下水是否通畅\r\nD、挪动桌椅时禁止拖、拉，需要搬起',0,'A',0),(18,'公司禁止使用的工具是',4,'A、百洁布     \r\nB、清洁刷    \r\nC、钢丝球    \r\nD、抹布',0,'C',0),(19,'testet',7,'A、sdfsdf\r\nB\r\nC.67567',0,'B',3),(20,'北京',13,'A、别净\r\nB、大家好',0,'A',5),(21,'你需要多长时间做这个东西',15,'A、需要三天\r\nB、需要五天\r\nC、需要半个月',0,'A',5),(23,'特斯特天使特特',17,'A，沙特沙特他色天使饿死\r\nB，尔特瑞特\r\nC，发士大夫十分',0,'B',7),(24,'testet',18,'A,rtyty\r\nB，士大夫是\r\nC,大师傅士大夫\r\nD，士大夫是',0,'B',7),(25,'1是的',10,'阿萨德',0,'下次',4),(27,'测试题目2',30,'想法',0,'3',11),(28,'你在北京么',29,'A、 北京\r\nB、 天津\r\nC、石家庄',0,'A',11);

/*Table structure for table `et_signed` */

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

/*Data for the table `et_signed` */

/*Table structure for table `et_studylog` */

DROP TABLE IF EXISTS `et_studylog`;

CREATE TABLE `et_studylog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `courseware_id` int(11) NOT NULL DEFAULT '0' COMMENT '课件ID',
  `start_time` int(11) DEFAULT NULL COMMENT '开始时间',
  `end_time` int(11) DEFAULT NULL COMMENT '结束时间',
  `status` tinyint(1) DEFAULT NULL COMMENT '状态 0:未开始 1:学习中 2:考试未通过 3:考试通过',
  `classify_id` int(11) DEFAULT '1' COMMENT '问题所属分类id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8;

/*Data for the table `et_studylog` */

insert  into `et_studylog`(`id`,`student_id`,`courseware_id`,`start_time`,`end_time`,`status`,`classify_id`) values (9,1,1,2147483647,2147483647,3,1),(10,1,2,2147483647,2147483647,3,1),(11,1,3,2147483647,2147483647,3,1),(12,1,4,2015,2015,3,1),(13,92,1,1438315423,1438315436,3,1),(14,92,2,1438321745,1438321751,3,1),(15,92,3,1438395422,1438395424,3,1),(16,92,4,1438395439,1438395443,2,1),(17,114,1,1439967430,1438668167,3,1),(18,114,2,1438668174,1438855236,2,1),(19,134,1,1438693015,1438693025,3,1),(20,134,2,1438693035,1438693041,2,1),(21,138,1,1438743603,1438743606,3,1),(22,138,2,1438743631,1438743637,3,1),(23,138,3,1438743650,1438743653,2,1),(24,153,1,1438759093,NULL,1,1),(25,114,3,1438773604,1438831110,3,1),(26,234,1,1438852978,1438852981,1,1),(27,114,4,1438855244,1438855247,2,1),(28,241,1,1438861521,1438861530,3,1),(29,241,2,1438861664,1438861667,3,1),(30,241,3,1438861670,1438861675,3,1),(31,241,4,1438861678,1438861680,3,1),(32,244,1,1438863507,1438863531,1,1),(33,246,1,1438913215,1438913260,3,1),(34,246,2,1438913270,1438913272,3,1),(35,246,3,1438913275,1438913277,3,1),(36,246,4,1438913281,1438913283,3,1),(37,279,1,1438934493,1438934496,1,1),(38,296,1,1438949816,1438949819,3,1),(39,296,2,1438949830,1438949834,3,1),(40,296,3,1438949841,1438949846,3,1),(41,296,4,1438949851,1438949854,3,1),(42,291,1,1439954031,1439171620,3,1),(43,298,1,1439200762,NULL,1,1),(44,291,2,1439536804,1439536807,3,1),(45,291,3,1439536810,1439536813,3,1),(46,291,4,1439536822,1439536825,3,1),(47,291,5,1439980664,1439806604,1,2),(48,114,5,1439973538,NULL,3,2),(49,291,10,1440035116,1439887838,3,4),(50,291,13,1439887547,1439887549,3,1),(51,114,13,1439892986,1439893013,3,1),(55,291,15,1439979279,1439952752,3,5),(56,114,7,1439951057,NULL,1,1),(57,291,16,1439972571,1439971974,1,6),(58,114,16,1439973205,1439973177,1,6),(59,114,15,1439973348,NULL,2,5),(60,114,10,1439973363,1439973371,3,4),(61,291,7,1439999555,NULL,1,3),(62,291,17,1440035228,NULL,3,7),(63,302,10,1440136683,NULL,1,4),(64,302,20,1440140728,NULL,1,5),(65,302,17,1440140827,NULL,1,7),(66,302,28,1440137106,NULL,1,10),(67,302,29,1440141807,NULL,1,11);

/*Table structure for table `et_user` */

DROP TABLE IF EXISTS `et_user`;

CREATE TABLE `et_user` (
  `common_mobile` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '常用的手机号',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '姓名',
  `auth_key` char(32) CHARACTER SET utf8 DEFAULT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '加密的密码',
  `password_reset_token` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '密码重置',
  `email` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '邮箱',
  `idnumber` varchar(24) CHARACTER SET utf8 DEFAULT '' COMMENT '身份证号',
  `age` smallint(3) DEFAULT NULL COMMENT '年龄',
  `birthday` date DEFAULT NULL COMMENT '生日',
  `mobile` varchar(15) CHARACTER SET utf8 DEFAULT '' COMMENT '手机',
  `ecp` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '紧急联系人',
  `ecn` varchar(15) CHARACTER SET utf8 DEFAULT NULL COMMENT '紧急联系号码',
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '住址',
  `city` char(3) CHARACTER SET utf8 DEFAULT NULL COMMENT '市',
  `province` char(3) CHARACTER SET utf8 DEFAULT NULL COMMENT '省',
  `district` char(2) CHARACTER SET utf8 DEFAULT NULL COMMENT '区',
  `whatodo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '提供的服务列表',
  `from_type` int(10) DEFAULT NULL COMMENT '来自哪里',
  `when` int(10) DEFAULT NULL COMMENT '选择服务时间',
  `status` smallint(6) DEFAULT NULL COMMENT '状态',
  `created_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `updated_at` int(11) DEFAULT NULL COMMENT '更新时间',
  `isdel` char(1) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '1',
  `study_status` smallint(1) DEFAULT '1' COMMENT '学习状态:1未学习，2学习中， 3未通过，4已通过',
  `study_time` int(11) DEFAULT NULL COMMENT '学习所用时长（单位秒）',
  `notice_status` smallint(1) DEFAULT NULL COMMENT '通知状态：1已通知，2未通知',
  `online_exam_time` int(11) DEFAULT NULL COMMENT '现场在线考试时间（开始还是结束呢？）',
  `online_exam_score` int(11) DEFAULT NULL COMMENT '现场在线考试成绩',
  `online_exam_mode` smallint(1) DEFAULT NULL COMMENT '现场在线考试方式：空：未安排，1：手机考试，2：电脑考试',
  `exam_result` smallint(1) DEFAULT NULL COMMENT '现场在线考试结果：1通过，2未通过',
  `operation_time` int(11) DEFAULT NULL COMMENT '实操考试时间',
  `operation_score` int(11) DEFAULT NULL COMMENT '实操考试结果',
  `test_status` smallint(1) DEFAULT NULL COMMENT '试工状态：1.安排试工，2：不用试工',
  `test_situation` char(11) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '试工情况',
  `test_result` int(11) DEFAULT NULL COMMENT '试工结果',
  `sign_status` smallint(1) DEFAULT NULL COMMENT '签约状态1.已签约，2：未解约',
  `user_status` int(3) DEFAULT NULL COMMENT '用户个人详细信息是否已经添加，1添加，2未添加',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=303 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户报名表';

/*Data for the table `et_user` */

insert  into `et_user`(`common_mobile`,`id`,`username`,`auth_key`,`password_hash`,`password_reset_token`,`email`,`idnumber`,`age`,`birthday`,`mobile`,`ecp`,`ecn`,`address`,`city`,`province`,`district`,`whatodo`,`from_type`,`when`,`status`,`created_at`,`updated_at`,`isdel`,`study_status`,`study_time`,`notice_status`,`online_exam_time`,`online_exam_score`,`online_exam_mode`,`exam_result`,`operation_time`,`operation_score`,`test_status`,`test_situation`,`test_result`,`sign_status`,`user_status`) values (NULL,92,'qqqq',NULL,'123123',NULL,NULL,'1111',NULL,'0000-00-00','11111',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1222222222,NULL,NULL,4,NULL,1,90,90,2,1,NULL,1,1,'',1,2,NULL),(NULL,114,'士大夫',NULL,NULL,NULL,NULL,'123456789012345678',NULL,NULL,'15110249233',NULL,'',NULL,NULL,NULL,NULL,'a:1:{i:0;s:1:\"1\";}',2,2,NULL,NULL,NULL,NULL,4,244609,1,1438943234,0,2,2,NULL,NULL,NULL,'11',0,NULL,NULL),(NULL,134,'张航',NULL,NULL,NULL,NULL,'11011011011011101110110',NULL,NULL,'18618390057',NULL,NULL,NULL,NULL,NULL,NULL,'1',0,0,NULL,NULL,NULL,NULL,2,NULL,1,NULL,NULL,1,NULL,NULL,1,1,'0',2,NULL,NULL),(NULL,291,'大连',NULL,NULL,NULL,NULL,'123456789012345678',NULL,NULL,'18614079208',NULL,'',NULL,NULL,NULL,NULL,'a:1:{i:0;s:1:\"2\";}',3,3,NULL,1438944522,NULL,NULL,4,-1011061,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(NULL,294,'',NULL,NULL,NULL,NULL,'',NULL,NULL,'18711111113',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1438948720,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(NULL,295,'',NULL,NULL,NULL,NULL,'',NULL,NULL,'18612345678',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1438949073,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(NULL,296,'hu',NULL,NULL,NULL,NULL,'210211199913392345',NULL,NULL,'23910750274',NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1438949452,NULL,NULL,2,15,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(NULL,297,'',NULL,NULL,NULL,NULL,'',NULL,NULL,'13393266728',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1438949970,NULL,NULL,1,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(NULL,298,'',NULL,NULL,NULL,NULL,'',NULL,NULL,'18712344321',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1439200731,NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(NULL,299,'',NULL,NULL,NULL,NULL,'',NULL,NULL,'13212345678',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1439260376,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(NULL,300,'溜溜',NULL,NULL,NULL,NULL,'123456789012345678',NULL,NULL,'18614079200',NULL,'',NULL,NULL,NULL,NULL,'a:1:{i:0;s:1:\"2\";}',2,2,NULL,1439536035,NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(NULL,301,'黄阿姨',NULL,NULL,NULL,NULL,'440103198812120909',NULL,NULL,'13560009440',NULL,'13560009440',NULL,NULL,NULL,NULL,'a:1:{i:0;s:1:\"1\";}',1,1,NULL,1440130078,NULL,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(NULL,302,'测试',NULL,NULL,NULL,NULL,'111111111111111111',NULL,NULL,'13673526139',NULL,'',NULL,NULL,NULL,NULL,'a:1:{i:0;s:1:\"1\";}',1,1,NULL,1440131236,NULL,NULL,3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);

/*Table structure for table `migration` */

DROP TABLE IF EXISTS `migration`;

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `migration` */

insert  into `migration`(`version`,`apply_time`) values ('m000000_000000_base',1438409496),('m140608_201405_user_init',1438409506),('m140608_201406_rbac_init',1438409506),('m140708_201431_rbac_init',1438409578);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
