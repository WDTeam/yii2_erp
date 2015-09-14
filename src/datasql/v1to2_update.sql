/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

USE `train`;

/* Alter table in target */
ALTER TABLE `et_answerlog` 
	ADD COLUMN `classify_id` int(11)   NULL COMMENT '问题所属分类id' after `is_correct` ;

/* Create table in target */
CREATE TABLE `et_cache`(
	`id` char(128) COLLATE utf8_general_ci NOT NULL  , 
	`expire` int(11) NULL  , 
	`data` blob NULL  , 
	PRIMARY KEY (`id`) 
) ENGINE=InnoDB DEFAULT CHARSET='utf8' COLLATE='utf8_general_ci';


/* Create table in target */
CREATE TABLE `et_category`(
	`cateid` int(11) NOT NULL  auto_increment COMMENT '分类ID' , 
	`catename` varchar(30) COLLATE utf8_general_ci NULL  COMMENT '分类名称' , 
	`description` text COLLATE utf8_general_ci NULL  COMMENT '分类描述' , 
	PRIMARY KEY (`cateid`) 
) ENGINE=InnoDB DEFAULT CHARSET='utf8' COLLATE='utf8_general_ci';


/* Alter table in target */
ALTER TABLE `et_courseware` 
	CHANGE `order_number` `order_number` int(4)   NULL DEFAULT 9999 COMMENT '在分类下的排序' after `pv` , 
	ADD COLUMN `classify_id` int(11)   NOT NULL DEFAULT 0 COMMENT '服务技能ID' after `order_number` , 
	DROP COLUMN `classify` ;

/* Alter table in target */
ALTER TABLE `et_question` 
	ADD COLUMN `category_id` int(11)   NULL DEFAULT 0 COMMENT '服务ID' after `correct_options` ;

/* Alter table in target */
ALTER TABLE `et_studylog` 
	ADD COLUMN `classify_id` int(11)   NULL DEFAULT 1 COMMENT '问题所属分类id' after `status` ;

/* Alter table in target */
ALTER TABLE `et_user` 
	CHANGE `username` `username` varchar(255)  COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '姓名' after `id` , 
	CHANGE `idnumber` `idnumber` varchar(24)  COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '身份证号' after `email` , 
	CHANGE `mobile` `mobile` varchar(15)  COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '手机' after `birthday` , 
	CHANGE `whatodo` `whatodo` varchar(255)  COLLATE utf8_unicode_ci NULL COMMENT '提供的服务列表' after `district` , 
	CHANGE `from_type` `from_type` int(10)   NULL COMMENT '来自哪里' after `whatodo` , 
	CHANGE `when` `when` int(10)   NULL COMMENT '选择服务时间' after `from_type` , 
	CHANGE `study_status` `study_status` smallint(1)   NULL DEFAULT 1 COMMENT '学习状态:1未学习，2学习中， 3未通过，4已通过' after `isdel` , 
	CHANGE `online_exam_time` `online_exam_time` int(11)   NULL COMMENT '现场在线考试时间（开始还是结束呢？）' after `notice_status` , 
	CHANGE `online_exam_score` `online_exam_score` int(11)   NULL COMMENT '现场在线考试成绩' after `online_exam_time` , 
	CHANGE `online_exam_mode` `online_exam_mode` smallint(1)   NULL COMMENT '现场在线考试方式：空：未安排，1：手机考试，2：电脑考试' after `online_exam_score` , 
	CHANGE `exam_result` `exam_result` smallint(1)   NULL COMMENT '现场在线考试结果：1通过，2未通过' after `online_exam_mode` , 
	CHANGE `operation_score` `operation_score` int(11)   NULL COMMENT '实操考试结果' after `operation_time` , 
	CHANGE `test_situation` `test_situation` char(11)  COLLATE utf8_unicode_ci NULL COMMENT '试工情况' after `test_status` , 
	CHANGE `sign_status` `sign_status` smallint(1)   NULL COMMENT '签约状态1.已签约，2：未解约' after `test_result` , 
	ADD COLUMN `user_status` int(3)   NULL COMMENT '用户个人详细信息是否已经添加，1添加，2未添加' after `sign_status` ;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;