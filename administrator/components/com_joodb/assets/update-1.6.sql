DROP TABLE IF EXISTS `#__joodb_settings`;
CREATE TABLE IF NOT EXISTS `#__joodb_settings` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(127) NOT NULL,
  `value` varchar(254) NOT NULL,
  `jb_id` int(1) DEFAULT NULL,
  `changed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `jb_id` (`jb_id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Settings table for JooDatabase' AUTO_INCREMENT=2 ;

