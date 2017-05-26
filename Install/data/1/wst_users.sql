
SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `wst_users`
-- ----------------------------
DROP TABLE IF EXISTS `wst_users`;
CREATE TABLE `wst_users` (
  `userId` int(11) NOT NULL AUTO_INCREMENT,
  `loginName` varchar(20) NOT NULL,
  `loginSecret` int(11) NOT NULL,
  `loginPwd` varchar(50) NOT NULL,
  `userSex` tinyint(4) DEFAULT '0',
  `userType` tinyint(4) DEFAULT '0',
  `userName` varchar(20) DEFAULT NULL,
  `userQQ` varchar(20) DEFAULT NULL,
  `userPhone` char(11) DEFAULT NULL,
  `userEmail` varchar(50) DEFAULT NULL,
  `userScore` int(11) DEFAULT '0',
  `userPhoto` varchar(150) DEFAULT NULL,
  `userTotalScore` int(11) DEFAULT '0',
  `userStatus` tinyint(4) DEFAULT '1',
  `userFlag` tinyint(4) DEFAULT '1',
  `createTime` datetime DEFAULT NULL,
  `lastIP` varchar(16) DEFAULT NULL,
  `lastTime` datetime DEFAULT NULL,
  `userFrom` tinyint(4) DEFAULT '0',
  `openId` varchar(50) DEFAULT NULL,
  `wxOpenId` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`userId`),
  KEY `userStatus` (`userStatus`,`userFlag`),
  KEY `loginName` (`loginName`),
  KEY `userPhone` (`userPhone`),
  KEY `userEmail` (`userEmail`),
  KEY `userType` (`userType`,`userFlag`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wst_users
-- ----------------------------
INSERT INTO `wst_users` VALUES ('9', 'gd_guangzhou', '7902', 'd6a3fe736d32101b2070e4d7667db639', '0', '1', '广东广州店铺', null, '15918671994', null, '4000', null, '4000', '1', '1', '2015-05-08 10:29:39', '127.0.0.1', '2016-09-05 21:45:43', '0', null, null);
INSERT INTO `wst_users` VALUES ('10', 'gd_shenzhen', '1679', '7fdc1a0615463a521a59d1da3fb49e5b', '0', '1', '广东深圳店铺', null, '15918671194', null, '0', null, '0', '1', '1', '2015-05-08 10:34:26', null, null, '0', null, null);
INSERT INTO `wst_users` VALUES ('11', 'gd_zhuhai', '9254', '5dd44b6e13011dcaec803ebb71eee9ed', '0', '1', '广东珠海店铺', null, '15918671294', null, '0', null, '0', '1', '1', '2015-05-08 10:35:55', null, null, '0', null, null);
INSERT INTO `wst_users` VALUES ('12', 'gd_shantou', '9880', 'ab9496339c7c82a54e04ed88cef3a88e', '0', '1', '广东汕头店铺', null, '15918671394', null, '0', null, '0', '1', '1', '2015-05-08 10:37:31', null, null, '0', null, null);
INSERT INTO `wst_users` VALUES ('13', 'gd_shaoguan', '5234', '6dc085c2ffe8d99a1cce1f7d772ab23b', '0', '1', '广东韶关店铺', null, '15918671494', null, '0', null, '0', '1', '1', '2015-05-08 10:38:47', null, null, '0', null, null);
INSERT INTO `wst_users` VALUES ('14', 'gd_foshan', '3896', 'f40f40cbe332a15292f9324d962422a2', '0', '1', '广东佛山店铺', null, '15918671594', null, '0', null, '0', '1', '1', '2015-05-08 10:39:51', null, null, '0', null, null);
INSERT INTO `wst_users` VALUES ('15', '13763316008_b', '6834', 'fc9fb3a4db2ebb524be31925435987dc', '0', '1', '张测试', '', '13763316008', '', '0', null, '0', '1', '1', '2015-05-10 22:49:23', '101.46.63.120', '2015-06-01 21:22:19', '0', null, null);
INSERT INTO `wst_users` VALUES ('16', 'ceshi1', '2885', '7e9b1a3e2a390f5da94b1b36e754c976', '0', '1', '测试店铺1', null, '15918671993', null, '0', null, '0', '1', '1', '2015-05-12 22:16:17', '103.199.87.218', '2015-05-31 22:43:31', '0', null, null);
INSERT INTO `wst_users` VALUES ('17', 'ceshi2', '4644', '202cd234d8619d8b26ab8b2197e965ad', '0', '1', '测试店铺2', '', '15918671694', '', '0', null, '0', '1', '1', '2015-05-12 22:17:50', '103.199.87.218', '2015-05-31 17:18:26', '0', null, null);
INSERT INTO `wst_users` VALUES ('18', 'ceshi3', '8150', 'eabc4d39c913b6e78f4756ae2a1daf2a', '0', '1', '测试店铺3', '', '15918671794', '', '0', null, '0', '1', '1', '2015-05-12 22:32:48', '103.199.87.218', '2015-05-31 21:58:08', '0', null, null);
INSERT INTO `wst_users` VALUES ('19', 'ceshi21', '4334', '060ee46489799f04ad97b3d11f49f2b8', '0', '1', '测试店铺21', null, '15918671894', null, '0', null, '0', '1', '1', '2015-05-13 15:07:05', '223.73.155.87', '2015-05-26 21:52:51', '0', null, null);
INSERT INTO `wst_users` VALUES ('20', 'ceshi22', '1466', '453e4fd1d9a7d33a6f5c4bd0bb1fc44b', '0', '1', '测试店铺22', null, '15918671094', null, '0', null, '0', '1', '1', '2015-05-13 15:08:14', '103.199.87.218', '2015-06-01 08:32:52', '0', null, null);
INSERT INTO `wst_users` VALUES ('21', 'ceshi23', '4380', 'cd9a2715fdb1c8771122f978903ca74e', '0', '1', '测试店铺23', null, '15918670994', null, '0', null, '0', '1', '1', '2015-05-13 15:09:24', null, null, '0', null, null);
INSERT INTO `wst_users` VALUES ('22', 'ceshi24', '3677', '06521bce3982f140212989e332488e72', '0', '1', '测试店铺24', null, '15928671994', null, '0', null, '0', '1', '1', '2015-05-13 15:10:47', null, null, '0', null, null);
INSERT INTO `wst_users` VALUES ('23', '865984518_g', '9799', 'd5e8ee584602dce671a305c69da92f0e', '0', '0', '', '', '', '865984518@qq.com', '0', null, '0', '1', '1', '2015-05-21 14:08:24', null, null, '0', null, null);
INSERT INTO `wst_users` VALUES ('24', 'ceshi31', '2518', 'fc9fb3a4db2ebb524be31925435987dc', '0', '1', '测试店铺31', '', '15918671996', '', '0', null, '0', '1', '1', '2015-05-26 21:06:03', '211.136.253.232', '2015-05-27 10:17:08', '0', null, null);
INSERT INTO `wst_users` VALUES ('25', 'ceshi32', '8937', '2e59831193563c21a51b275788229f98', '0', '1', '测试店铺32', '', '15918671997', '', '0', null, '0', '1', '1', '2015-05-26 21:08:13', '103.199.87.218', '2015-06-01 11:34:34', '0', null, null);
INSERT INTO `wst_users` VALUES ('26', 'ceshi33', '1867', '845aa09ad90841fccdf865def2c80755', '0', '1', '测试店铺33', '', '15918671991', '', '0', null, '0', '1', '1', '2015-05-26 21:09:53', '103.199.87.218', '2015-06-01 11:53:52', '0', null, null);
INSERT INTO `wst_users` VALUES ('27', 'ceshi34', '4588', 'fc9fb3a4db2ebb524be31925435987dc', '0', '1', '测试店铺34', '', '15918671998', '', '0', null, '0', '1', '1', '2015-05-26 21:11:23', null, null, '0', null, null);
INSERT INTO `wst_users` VALUES ('28', 'ceshi41', '7274', 'fc9fb3a4db2ebb524be31925435987dc', '0', '1', '测试店铺41', '', '15918671992', '', '0', null, '0', '1', '1', '2015-05-26 21:14:00', '211.136.253.198', '2015-05-27 14:54:22', '0', null, null);
INSERT INTO `wst_users` VALUES ('29', 'ceshi42', '4880', '947a7d3678d8bd05c7f21cfd0c5be50d', '0', '1', '测试店铺42', '', '15918671990', '', '0', null, '0', '1', '1', '2015-05-26 21:20:27', '103.199.87.218', '2015-06-01 19:04:36', '0', null, null);
INSERT INTO `wst_users` VALUES ('30', 'ceshi43', '1630', 'fc9fb3a4db2ebb524be31925435987dc', '0', '1', '测试店铺43', '', '15918671989', '', '0', null, '0', '1', '1', '2015-05-26 21:22:06', null, null, '0', null, null);
INSERT INTO `wst_users` VALUES ('31', 'ceshi44', '4123', 'fc9fb3a4db2ebb524be31925435987dc', '0', '1', '测试店铺44', '', '15918671980', '', '0', null, '0', '1', '1', '2015-05-26 21:24:36', null, null, '0', null, null);
INSERT INTO `wst_users` VALUES ('32', 'ceshi51', '3092', 'fc9fb3a4db2ebb524be31925435987dc', '0', '1', '测试店铺51', '', '15918671988', '', '0', null, '0', '1', '1', '2015-05-26 21:30:17', '223.73.155.87', '2015-05-27 20:55:46', '0', null, null);
INSERT INTO `wst_users` VALUES ('33', 'ceshi52', '9160', '6276f96ad761e03f633d1a1b484156d8', '0', '1', '测试店铺52', '', '15918671881', '', '0', null, '0', '1', '1', '2015-05-26 21:32:01', '103.199.87.218', '2015-06-01 20:32:34', '0', null, null);
INSERT INTO `wst_users` VALUES ('34', 'ceshi53', '9425', 'fc9fb3a4db2ebb524be31925435987dc', '0', '1', '测试店铺53', '', '15918671812', '', '0', null, '0', '1', '1', '2015-05-26 21:33:27', null, null, '0', null, null);
INSERT INTO `wst_users` VALUES ('35', 'ceshi54', '3157', 'fc9fb3a4db2ebb524be31925435987dc', '0', '1', '测试店铺54', '', '15918671834', '', '0', null, '0', '1', '1', '2015-05-26 21:40:20', null, null, '0', null, null);
INSERT INTO `wst_users` VALUES ('36', 'ceshi61', '1934', 'fc9fb3a4db2ebb524be31925435987dc', '0', '1', '测试店铺61', '', '15918671833', '', '0', null, '0', '1', '1', '2015-05-26 21:42:59', '223.73.155.87', '2015-05-27 22:24:04', '0', null, null);
INSERT INTO `wst_users` VALUES ('37', 'ceshi62', '1996', '0c3a4081280a5b80af5b70967cb6252a', '0', '1', '测试店铺62', '', '15918671867', '', '0', null, '0', '1', '1', '2015-05-26 21:45:22', '103.199.87.218', '2015-06-01 21:44:20', '0', null, null);
INSERT INTO `wst_users` VALUES ('38', 'ceshi63', '8163', 'fc9fb3a4db2ebb524be31925435987dc', '0', '1', '测试店铺63', '', '15918671987', '', '0', null, '0', '1', '1', '2015-05-26 21:47:10', null, null, '0', null, null);
INSERT INTO `wst_users` VALUES ('39', 'ceshi64', '3204', '5d50361a4cc2359475c8d08c34fbfcd4', '0', '1', '测试店铺64', '', '15918671857', '', '0', null, '0', '1', '1', '2015-05-26 21:48:43', null, null, '0', null, null);
