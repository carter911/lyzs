/*
 Navicat MySQL Data Transfer

 Source Server         : 施工宝RDS
 Source Server Type    : MySQL
 Source Server Version : 50718
 Source Host           : rm-bp130l46o160s3sv9o.mysql.rds.aliyuncs.com
 Source Database       : sgb_test

 Target Server Type    : MySQL
 Target Server Version : 50718
 File Encoding         : utf-8

 Date: 10/25/2018 15:03:08 PM
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `sgb_oa_meeting_group`
-- ----------------------------
DROP TABLE IF EXISTS `sgb_oa_meeting_group`;
CREATE TABLE `sgb_oa_meeting_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `meeting_id` int(11) DEFAULT NULL COMMENT '会议记录id',
  `group_id` int(11) DEFAULT NULL COMMENT '群组id',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `meetgroup` (`meeting_id`,`group_id`) USING HASH
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;
