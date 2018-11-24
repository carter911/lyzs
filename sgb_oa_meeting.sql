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

 Date: 10/25/2018 15:02:10 PM
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `sgb_oa_meeting`
-- ----------------------------
DROP TABLE IF EXISTS `sgb_oa_meeting`;
CREATE TABLE `sgb_oa_meeting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL COMMENT '用户',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `company_id` int(11) DEFAULT '0' COMMENT '所在公司',
  `title` text COMMENT '标题',
  `content` text COMMENT '内容',
  `desc` varchar(255) DEFAULT '' COMMENT '简介',
  `look_list` text COMMENT '查看人列表',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `sgb_oa_meeting`
-- ----------------------------
BEGIN;
INSERT INTO `sgb_oa_meeting` VALUES ('30', null, '2018-09-19 15:40:07', '2018-09-19 15:40:07', '123', '中午要吃饱，晚上才有劲撸代码', '&lt;p&gt;中午要吃饱，晚上才有劲撸代码中午要吃饱，晚上才有劲撸代码中午要吃饱，晚上才有劲撸代码中午要吃饱，晚上才有劲撸代码中午要吃饱，晚上才有劲撸代码中午要吃饱，晚上才有劲撸代码中午要吃饱，晚上才有劲撸代码中午要吃饱，晚上才有劲撸代码&lt;img src=&quot;http://images.e-shigong.com/image/avatar/1537342798_218378.jpg?imageslim&quot; alt=&quot;a96efba0b097e1bc86a14e49295a548d8dea0c8363bcc-yVaRTV_fw658&quot; style=&quot;max-width: 100%;&quot;&gt;&lt;/p&gt;&lt;p&gt;&lt;br&gt;&lt;/p&gt;', '中午要吃饱，晚上才有劲撸代码中午要吃饱，晚上才有劲撸代码', null);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
