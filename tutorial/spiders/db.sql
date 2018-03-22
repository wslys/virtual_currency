DROP TABLE IF EXISTS `historical_data`;
CREATE TABLE `historical_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `old_date` datetime DEFAULT NULL COMMENT '时间',
  `open` varchar(255) DEFAULT NULL,
  `hight` varchar(255) DEFAULT NULL,
  `low` varchar(255) DEFAULT NULL,
  `close` varchar(255) DEFAULT NULL COMMENT '收盘（ close ）',
  `volume` varchar(255) DEFAULT NULL COMMENT '交易量（ volume ）',
  `market_cap` varchar(255) DEFAULT NULL COMMENT '总市值 （ Market Cap ）',
  `create_at` time DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for markets
-- ----------------------------
DROP TABLE IF EXISTS `markets`;
CREATE TABLE `markets` (
  `id` int(11) NOT NULL,
  `source` varchar(255) DEFAULT NULL,
  `pair` varchar(255) DEFAULT NULL,
  `volume` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `volumes` varchar(255) DEFAULT NULL,
  `create_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `currencys`;
CREATE TABLE `currencys` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '货币名称',
  `market_cap` varchar(255) DEFAULT NULL COMMENT '流通市值',
  `price` varchar(255) DEFAULT NULL COMMENT '价格',
  `volume_24h` varchar(255) DEFAULT NULL COMMENT '收盘（ close ）',
  `change_24h` varchar(255) DEFAULT NULL COMMENT '流通数量',
  `price_graph_7d` varchar(255) DEFAULT NULL COMMENT '交易量（ volume ）',
  `circulating_supply` varchar(255) DEFAULT NULL COMMENT '总市值 （ Market Cap ）',
  `create_at` time DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
