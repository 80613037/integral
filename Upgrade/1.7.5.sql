ALTER TABLE wst_shops ADD isDistributAll tinyint default 0;

ALTER TABLE wst_cart ADD packageId int default 0;
ALTER TABLE wst_cart ADD batchNo bigint(20) default 0;


DROP TABLE IF EXISTS `wst_goods_packages`;
CREATE TABLE `wst_goods_packages` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `packageId` int(11) DEFAULT '0',
  `goodsId` int(11) DEFAULT '0',
  `diffPrice` int(11) DEFAULT '0',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `wst_packages`;
CREATE TABLE `wst_packages` (
  `packageId` int(11) NOT NULL AUTO_INCREMENT,
  `shopId` int(11) DEFAULT '0',
  `goodsId` int(11) DEFAULT '0',
  `packageName` varchar(100) DEFAULT NULL,
  `createTime` datetime DEFAULT NULL,
  PRIMARY KEY (`packageId`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;