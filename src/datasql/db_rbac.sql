# Host: localhost  (Version: 5.5.40)
# Date: 2015-08-01 19:53:27
# Generator: MySQL-Front 5.3  (Build 4.120)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "et_adminuser"
#

DROP TABLE IF EXISTS `et_adminuser`;
CREATE TABLE `et_adminuser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user',
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `role` (`role`),
  KEY `status` (`status`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# Data for table "et_adminuser"
#

INSERT INTO `et_adminuser` VALUES (1,'admin','1epI5YqrEp69yYopnIupWzaIbpbG45-M','$2y$13$H2h2XPss7i.FPQ3lCHamQu/qjqx8jEEFXwTR3vXdxpxQY.SpKBFSS','','admin@demo.com','admin',1,1438409505,1438409505);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Data for table "et_auth_assignment"
#

INSERT INTO `et_auth_assignment` VALUES ('admin','1',1438409506),('author','1',1438409578),('reader','1',1438409578);

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
